<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('user_id', Auth::id())
            ->with(['images' => function($query) {
                $query->orderBy('order', 'asc');
            }])
            ->with('bookings', 'ratings')
            ->latest()
            ->get();
        
        $stats = [
            'total' => $properties->count(),
            'active' => $properties->where('admin_status', 'approved')->where('is_suspended', false)->count(),
            'pending' => $properties->where('admin_status', 'pending')->count(),
            'suspended' => $properties->where('is_suspended', true)->count(),
        ];
        
        return view('owner.properties.index', compact('properties', 'stats'));
    }

    public function create()
    {
        return view('owner.properties.create');
    }

    public function store(Request $request)
    {
        // Check if room rentable is enabled
        $isRoomRentable = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        
        // Convert empty string values to null for integer fields before validation
        // If is_room_rentable is checked, remove only the scalar 'rooms' field (not the array)
        if ($isRoomRentable) {
            // Only remove the scalar 'rooms' field (number of rooms), not the 'rooms' array
            // Check if 'rooms' exists as a scalar value (not an array)
            if ($request->has('rooms') && !is_array($request->input('rooms'))) {
                $request->request->remove('rooms');
                $request->query->remove('rooms');
            }
        } elseif ($request->has('rooms') && !is_array($request->input('rooms'))) {
            // Only process if 'rooms' is a scalar value (not an array)
            $roomsValue = $request->input('rooms');
            if ($roomsValue === '' || $roomsValue === null || !is_numeric($roomsValue)) {
                $request->merge(['rooms' => null]);
            } else {
                $request->merge(['rooms' => (int)$roomsValue]);
            }
        }
        
        if ($request->has('bathrooms')) {
            $bathroomsValue = $request->input('bathrooms');
            if ($bathroomsValue === '' || !is_numeric($bathroomsValue)) {
                $request->merge(['bathrooms' => null]);
            } else {
                $request->merge(['bathrooms' => (int)$bathroomsValue]);
            }
        }
        
        if ($request->has('area')) {
            $areaValue = $request->input('area');
            if ($areaValue === '' || !is_numeric($areaValue)) {
                $request->merge(['area' => null]);
            } else {
                $request->merge(['area' => (int)$areaValue]);
            }
        }

        try {
        $validated = $request->validate([
            'ownership_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'property_type_id' => 'required|exists:property_types,id',
            'address' => 'required|string|max:500',
            'location_lat' => 'nullable|string',
            'location_lng' => 'nullable|string',
            'status' => 'required|in:furnished,unfurnished',
            'price' => 'required_if:is_room_rentable,0|nullable|numeric|min:0',
            'price_type' => 'required_if:is_room_rentable,0|nullable|in:daily,monthly,yearly',
            'contract_duration' => 'nullable|integer|min:1',
            'annual_increase' => 'nullable|numeric|min:0|max:100',
            'video_url' => 'nullable|url',
            'special_requirements' => 'nullable|string',
            'available_dates' => 'nullable|array',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'area' => 'nullable|integer|min:1',
            'rooms' => 'exclude_if:is_room_rentable,1|nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|string|max:100',
            'amenities' => 'nullable|array',
            'is_room_rentable' => 'nullable|boolean',
            'total_rooms' => 'required_if:is_room_rentable,1|nullable|integer|min:1',
            'contract_duration_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'rooms.*.description' => 'nullable|string',
                'rooms.*.price' => 'nullable|numeric|min:0',
                'rooms.*.price_type' => 'nullable|in:daily,monthly,yearly',
            'rooms.*.area' => 'nullable|integer|min:1',
            'rooms.*.beds' => 'nullable|integer|min:1',
            'rooms.*.amenities' => 'nullable|array',
            'rooms.*.images' => 'required_if:is_room_rentable,1|nullable|array|min:1',
            'rooms.*.images.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        }

        // التحقق اليدوي من rooms.*.price و rooms.*.price_type عند تفعيل is_room_rentable
        $isRoomRentable = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        if ($isRoomRentable) {
            // Get rooms array - ensure it's always an array
            $roomsInput = $request->input('rooms');
            if (!is_array($roomsInput)) {
                $roomsInput = [];
            }
            
            if (!empty($roomsInput)) {
                $errors = [];
                foreach ($roomsInput as $roomIndex => $roomData) {
                    if (!is_array($roomData)) {
                        continue; // Skip if not an array (might be the scalar 'rooms' field)
                    }
                    if (empty($roomData['price'])) {
                        $errors["rooms.{$roomIndex}.price"] = 'حقل السعر مطلوب للغرفة ' . $roomIndex;
                    }
                    if (empty($roomData['price_type'])) {
                        $errors["rooms.{$roomIndex}.price_type"] = 'حقل نوع السعر مطلوب للغرفة ' . $roomIndex;
                    }
                }
                if (!empty($errors)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors($errors);
                }
            }
        }

        // رفع ملف إثبات الملكية
        if ($request->hasFile('ownership_proof')) {
            $validated['ownership_proof'] = $request->file('ownership_proof')->store('ownership_proofs', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['admin_status'] = 'pending';
        $validated['is_room_rentable'] = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        
        // If room rentable is enabled, set default values for price and price_type
        if ($validated['is_room_rentable']) {
            if (empty($validated['price'])) {
                $validated['price'] = 0; // Default value
            }
            if (empty($validated['price_type'])) {
                $validated['price_type'] = 'monthly'; // Default value
            }
        }
        
        // Handle amenities
        if ($request->has('amenities')) {
            $validated['amenities'] = $request->amenities;
        }

        $property = Property::create($validated);
        
        // حفظ الغرف إذا كانت الوحدة قابلة للإيجار بالغرفة
        if ($validated['is_room_rentable'] && isset($validated['total_rooms']) && $request->has('rooms')) {
            $imageService = new ImageService();
            $totalRooms = (int) $validated['total_rooms'];
            
            // إنشاء الغرف تلقائياً بناءً على عدد الغرف
            for ($i = 1; $i <= $totalRooms; $i++) {
                $roomKey = $i; // المفتاح في المصفوفة
                $roomData = $request->rooms[$roomKey] ?? null;
                
                if (!$roomData) {
                    continue;
                }
                
                // التحقق من وجود صور للغرفة
                if (!isset($roomData['images']) || !is_array($roomData['images']) || count($roomData['images']) === 0) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['rooms' => "يجب رفع صورة واحدة على الأقل للغرفة {$i}"]);
                }
                
                $roomImages = [];
                foreach ($roomData['images'] as $image) {
                    if ($image && $image->isValid()) {
                        // استخدام ImageService لتحسين وضغط الصور
                        $result = $imageService->upload($image, 'rooms/' . $property->id, false);
                        $roomImages[] = $result['path'];
                    }
                }
                
                if (count($roomImages) === 0) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['rooms' => "يجب رفع صورة واحدة على الأقل للغرفة {$i}"]);
                }
                
                // إنشاء الغرفة مع الترقيم التلقائي
                \App\Models\Room::create([
                    'property_id' => $property->id,
                    'room_number' => (string) $i, // رقم الغرفة تلقائياً
                    'room_name' => "غرفة {$i}", // اسم الغرفة تلقائياً
                    'description' => $roomData['description'] ?? null,
                    'price' => $roomData['price'],
                    'price_type' => $roomData['price_type'],
                    'area' => $roomData['area'] ?? null,
                    'beds' => $roomData['beds'] ?? 1,
                    'amenities' => $roomData['amenities'] ?? [],
                    'images' => $roomImages,
                    'is_available' => true,
                ]);
            }
        }

        // رفع الصور باستخدام ImageService
        if ($request->hasFile('images')) {
            $imageService = new ImageService();
            
            foreach ($request->file('images') as $index => $image) {
                $result = $imageService->upload($image, 'properties', true);
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $result['path'],
                    'thumbnail_path' => $result['thumbnail'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('owner.properties.index')
            ->with('success', 'تم رفع معلومات الوحدة بنجاح، في انتظار مراجعة الأدمن');
    }

    public function show(Property $property)
    {
        $this->authorize('view', $property);
        $property->load('images', 'bookings', 'inquiries', 'rooms');
        return view('owner.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        
        $property->load('images', 'rooms');
        return view('owner.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        
        // إذا كانت الوحدة مرفوضة وتم التعديل، نعيدها إلى قيد المراجعة
        $wasRejected = $property->admin_status === 'rejected' && Auth::user()->role === 'owner';

        // Check if room rentable is enabled
        $isRoomRentable = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        
        // Convert empty string values to null for integer fields before validation
        // If is_room_rentable is checked, remove only the scalar 'rooms' field (not the array)
        if ($isRoomRentable) {
            // Only remove the scalar 'rooms' field (number of rooms), not the 'rooms' array
            // Check if 'rooms' exists as a scalar value (not an array)
            if ($request->has('rooms') && !is_array($request->input('rooms'))) {
                $request->request->remove('rooms');
                $request->query->remove('rooms');
            }
        } elseif ($request->has('rooms') && !is_array($request->input('rooms'))) {
            // Only process if 'rooms' is a scalar value (not an array)
            $roomsValue = $request->input('rooms');
            if ($roomsValue === '' || $roomsValue === null || !is_numeric($roomsValue)) {
                $request->merge(['rooms' => null]);
            } else {
                $request->merge(['rooms' => (int)$roomsValue]);
            }
        }
        
        if ($request->has('bathrooms')) {
            $bathroomsValue = $request->input('bathrooms');
            if ($bathroomsValue === '' || !is_numeric($bathroomsValue)) {
                $request->merge(['bathrooms' => null]);
            } else {
                $request->merge(['bathrooms' => (int)$bathroomsValue]);
            }
        }
        
        if ($request->has('area')) {
            $areaValue = $request->input('area');
            if ($areaValue === '' || !is_numeric($areaValue)) {
                $request->merge(['area' => null]);
            } else {
                $request->merge(['area' => (int)$areaValue]);
            }
        }

        try {
        $validated = $request->validate([
            'ownership_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'property_type_id' => 'required|exists:property_types,id',
            'address' => 'required|string|max:500',
            'location_lat' => 'nullable|string',
            'location_lng' => 'nullable|string',
            'status' => 'required|in:furnished,unfurnished',
            'price' => 'required_if:is_room_rentable,0|nullable|numeric|min:0',
            'price_type' => 'required_if:is_room_rentable,0|nullable|in:daily,monthly,yearly',
            'contract_duration' => 'nullable|integer|min:1',
            'annual_increase' => 'nullable|numeric|min:0|max:100',
            'video_url' => 'nullable|url',
            'special_requirements' => 'nullable|string',
            'available_dates' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'area' => 'nullable|integer|min:1',
            'rooms' => 'exclude_if:is_room_rentable,1|nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|string|max:100',
            'amenities' => 'nullable|array',
            'is_room_rentable' => 'nullable|boolean',
            'total_rooms' => 'required_if:is_room_rentable,1|nullable|integer|min:1',
            'contract_duration_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'rooms.*.id' => 'nullable|exists:rooms,id',
            'rooms.*.description' => 'nullable|string',
                'rooms.*.price' => 'nullable|numeric|min:0',
                'rooms.*.price_type' => 'nullable|in:daily,monthly,yearly',
            'rooms.*.area' => 'nullable|integer|min:1',
            'rooms.*.beds' => 'nullable|integer|min:1',
            'rooms.*.amenities' => 'nullable|array',
            'rooms.*.images' => 'nullable|array',
            'rooms.*.images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'rooms.*.is_available' => 'nullable|boolean',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        }

        // التحقق اليدوي من rooms.*.price و rooms.*.price_type عند تفعيل is_room_rentable
        $isRoomRentable = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        if ($isRoomRentable) {
            // Get rooms array - ensure it's always an array
            $roomsInput = $request->input('rooms');
            if (!is_array($roomsInput)) {
                $roomsInput = [];
            }
            
            if (!empty($roomsInput)) {
                $errors = [];
                foreach ($roomsInput as $roomIndex => $roomData) {
                    if (!is_array($roomData)) {
                        continue; // Skip if not an array (might be the scalar 'rooms' field)
                    }
                    if (empty($roomData['price'])) {
                        $errors["rooms.{$roomIndex}.price"] = 'حقل السعر مطلوب للغرفة ' . $roomIndex;
                    }
                    if (empty($roomData['price_type'])) {
                        $errors["rooms.{$roomIndex}.price_type"] = 'حقل نوع السعر مطلوب للغرفة ' . $roomIndex;
                    }
                }
                if (!empty($errors)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors($errors);
                }
            }
        }

        if ($request->hasFile('ownership_proof')) {
            if ($property->ownership_proof) {
                Storage::disk('public')->delete($property->ownership_proof);
            }
            $validated['ownership_proof'] = $request->file('ownership_proof')->store('ownership_proofs', 'public');
        }

        $validated['is_room_rentable'] = $request->has('is_room_rentable') && $request->is_room_rentable == '1';
        
        // If room rentable is enabled, set default values for price and price_type
        if ($validated['is_room_rentable']) {
            if (empty($validated['price'])) {
                $validated['price'] = 0; // Default value
            }
            if (empty($validated['price_type'])) {
                $validated['price_type'] = 'monthly'; // Default value
            }
        }
        
        // Handle amenities
        if ($request->has('amenities')) {
            $validated['amenities'] = $request->amenities;
        }
        
        // إذا كانت الوحدة مرفوضة وتم التعديل، نعيدها إلى قيد المراجعة ونسحب سبب الرفض
        if ($wasRejected) {
            $validated['admin_status'] = 'pending';
            $validated['rejection_reason'] = null;
            $validated['admin_notes'] = null; // مسح ملاحظات الأدمن أيضاً
            
            Log::info('Property resubmitted for review after rejection', [
                'property_id' => $property->id,
                'owner_id' => Auth::id(),
            ]);
        }
        
        // تحديث الوحدة - استخدام update مباشرة
        try {
            $updated = $property->update($validated);
            
            if (!$updated) {
                Log::error('Failed to update property', [
                    'property_id' => $property->id,
                    'validated_data' => $validated
                ]);
                return redirect()->back()
                    ->with('error', 'حدث خطأ أثناء حفظ التغييرات. يرجى المحاولة مرة أخرى.')
                    ->withInput();
            }
            
            // تحديث البيانات من قاعدة البيانات
            $property->refresh();
            
        } catch (\Exception $e) {
            Log::error('Exception while updating property', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ التغييرات: ' . $e->getMessage())
                ->withInput();
        }
        
        // تحديث/إنشاء الغرف
        if ($validated['is_room_rentable'] && isset($validated['total_rooms'])) {
            $imageService = new ImageService();
            $totalRooms = (int) $validated['total_rooms'];
            $existingRooms = $property->rooms()->orderBy('id')->get();
            $existingRoomsCount = $existingRooms->count();
            
            // إذا تم تقليل عدد الغرف، احذف الغرف الزائدة
            if ($existingRoomsCount > $totalRooms) {
                $roomsToDelete = $existingRooms->slice($totalRooms);
                foreach ($roomsToDelete as $roomToDelete) {
                    $roomToDelete->delete();
                }
                $existingRooms = $existingRooms->take($totalRooms);
            }
            
            // تحديث الغرف الموجودة وإنشاء الجديدة
            if ($request->has('rooms')) {
                $existingRoomsArray = $existingRooms->values()->all();
                
                for ($i = 1; $i <= $totalRooms; $i++) {
                    $roomIndex = $i - 1;
                    
                    // البحث عن بيانات الغرفة من الطلب (قد تكون بالـ ID أو بالرقم)
                    $roomData = null;
                    $room = null;
                    
                    // البحث أولاً بالـ ID (للغرف الموجودة)
                    foreach ($request->rooms as $key => $data) {
                        if (isset($data['id']) && $data['id']) {
                            $foundRoom = $existingRooms->firstWhere('id', $data['id']);
                            if ($foundRoom && $roomIndex < count($existingRoomsArray) && $existingRoomsArray[$roomIndex]->id == $foundRoom->id) {
                                $room = $foundRoom;
                                $roomData = $data;
                                break;
                            }
                        }
                    }
                    
                    // إذا لم نجد بالـ ID، جرب البحث بالرقم
                    if (!$roomData && isset($request->rooms[$i])) {
                        $roomData = $request->rooms[$i];
                    }
                    
                    // إذا لم نجد بالرقم، جرب البحث بالـ ID المباشر
                    if (!$roomData && $roomIndex < count($existingRoomsArray)) {
                        $room = $existingRoomsArray[$roomIndex];
                        if (isset($request->rooms[$room->id])) {
                            $roomData = $request->rooms[$room->id];
                        }
                    }
                    
                    if ($roomIndex < count($existingRoomsArray)) {
                        // تحديث غرفة موجودة
                        if (!$room) {
                            $room = $existingRoomsArray[$roomIndex];
                        }
                        
                        $updateData = [
                            'room_number' => (string) $i, // تحديث رقم الغرفة تلقائياً
                            'room_name' => "غرفة {$i}", // تحديث اسم الغرفة تلقائياً
                        ];
                        
                        if ($roomData) {
                            $updateData['description'] = $roomData['description'] ?? $room->description;
                            $updateData['price'] = $roomData['price'] ?? $room->price;
                            $updateData['price_type'] = $roomData['price_type'] ?? $room->price_type;
                            $updateData['area'] = $roomData['area'] ?? $room->area;
                            $updateData['beds'] = $roomData['beds'] ?? $room->beds ?? 1;
                            $updateData['amenities'] = $roomData['amenities'] ?? $room->amenities ?? [];
                            $updateData['is_available'] = !isset($roomData['is_available']) || $roomData['is_available'] == '1' ? true : false; // Default to true if not specified
                        }
                        
                        // إضافة صور جديدة
                        if ($roomData && isset($roomData['images']) && is_array($roomData['images']) && count($roomData['images']) > 0) {
                            $newImages = $room->images ?? [];
                            foreach ($roomData['images'] as $image) {
                                if ($image && $image->isValid()) {
                                    $result = $imageService->upload($image, 'rooms/' . $property->id, false);
                                    $newImages[] = $result['path'];
                                }
                            }
                            $updateData['images'] = $newImages;
                        }
                        
                        $room->update($updateData);
                    } else {
                        // إنشاء غرفة جديدة
                        if ($roomData) {
                            $roomImages = [];
                            if (isset($roomData['images']) && is_array($roomData['images'])) {
                                foreach ($roomData['images'] as $image) {
                                    if ($image && $image->isValid()) {
                                        $result = $imageService->upload($image, 'rooms/' . $property->id, false);
                                        $roomImages[] = $result['path'];
                                    }
                                }
                            }
                            
                            \App\Models\Room::create([
                                'property_id' => $property->id,
                                'room_number' => (string) $i, // رقم الغرفة تلقائياً
                                'room_name' => "غرفة {$i}", // اسم الغرفة تلقائياً
                                'description' => $roomData['description'] ?? null,
                                'price' => $roomData['price'] ?? 0,
                                'price_type' => $roomData['price_type'] ?? 'monthly',
                                'area' => $roomData['area'] ?? null,
                                'beds' => $roomData['beds'] ?? 1,
                                'amenities' => $roomData['amenities'] ?? [],
                                'images' => $roomImages,
                                'is_available' => (isset($roomData['is_available']) && $roomData['is_available'] == '1') ? true : true, // Always true by default
                            ]);
                        }
                    }
                }
            } else {
                // إذا لم يتم إرسال بيانات الغرف، قم بترقيم الغرف الموجودة فقط
                $existingRoomsArray = $existingRooms->values()->all();
                foreach ($existingRoomsArray as $index => $room) {
                    $room->update([
                        'room_number' => (string) ($index + 1),
                        'room_name' => "غرفة " . ($index + 1),
                    ]);
                }
            }
        } else {
            // حذف جميع الغرف إذا تم إلغاء تفعيل الإيجار بالغرفة
            $property->rooms()->delete();
        }

        // رفع الصور باستخدام ImageService
        if ($request->hasFile('images')) {
            $imageService = new ImageService();
            
            foreach ($request->file('images') as $index => $image) {
                $result = $imageService->upload($image, 'properties', true);
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $result['path'],
                    'thumbnail_path' => $result['thumbnail'],
                    'order' => $property->images()->count() + $index,
                ]);
            }
        }

        if ($wasRejected) {
            $successMessage = 'تم تحديث معلومات الوحدة بنجاح. تم إعادة إرسالها للمراجعة وسيتم مراجعتها من قبل الإدارة قريباً.';
        } else {
            $successMessage = 'تم تحديث معلومات الوحدة بنجاح';
        }
            
        return redirect()->route('owner.properties.index')
            ->with('success', $successMessage);
    }

    /**
     * تحميل ملف إثبات الملكية
     */
    public function downloadOwnershipProof(Property $property)
    {
        $this->authorize('view', $property);
        
        if (!$property->ownership_proof) {
            abort(404, 'ملف إثبات الملكية غير موجود');
        }
        
        $filePath = storage_path('app/public/' . $property->ownership_proof);
        
        if (!file_exists($filePath)) {
            abort(404, 'الملف غير موجود على السيرفر');
        }
        
        $fileName = basename($property->ownership_proof);
        $mimeType = mime_content_type($filePath);
        
        return response()->download($filePath, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }
}
