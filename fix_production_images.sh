#!/bin/bash

# سكريبت شامل لإصلاح مشكلة الصور في Production
# استخدم: bash fix_production_images.sh

echo "=========================================="
echo "بدء إصلاح مشكلة الصور في Production"
echo "=========================================="
echo ""

# الألوان للرسائل
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. التحقق من APP_URL
echo -e "${YELLOW}[1/8]${NC} التحقق من APP_URL..."
if grep -q "APP_URL=http" .env 2>/dev/null || grep -q "APP_URL=https" .env 2>/dev/null; then
    APP_URL=$(grep "APP_URL=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    if [[ "$APP_URL" == *"localhost"* ]] || [[ "$APP_URL" == *"127.0.0.1"* ]]; then
        echo -e "${RED}❌ APP_URL يحتوي على localhost: $APP_URL${NC}"
        echo -e "${YELLOW}⚠️  يجب تحديث APP_URL في .env إلى رابط الموقع الفعلي${NC}"
    else
        echo -e "${GREEN}✅ APP_URL: $APP_URL${NC}"
    fi
else
    echo -e "${RED}❌ APP_URL غير موجود في .env${NC}"
fi
echo ""

# 2. مسح Cache
echo -e "${YELLOW}[2/8]${NC} مسح Cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✅ تم مسح Cache${NC}"
echo ""

# 3. التحقق من Symbolic Link
echo -e "${YELLOW}[3/8]${NC} التحقق من Symbolic Link..."
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f public/storage)
    STORAGE_PATH=$(realpath storage/app/public)
    if [ "$LINK_TARGET" == "$STORAGE_PATH" ]; then
        echo -e "${GREEN}✅ Symbolic Link موجود ويشير بشكل صحيح${NC}"
    else
        echo -e "${RED}❌ Symbolic Link يشير إلى مسار خاطئ${NC}"
        echo -e "${YELLOW}⚠️  حذف الرابط القديم وإنشاء رابط جديد...${NC}"
        rm -rf public/storage
        php artisan storage:link
    fi
elif [ -d "public/storage" ]; then
    echo -e "${RED}❌ public/storage موجود لكنه مجلد وليس رابط رمزي${NC}"
    echo -e "${YELLOW}⚠️  حذف المجلد وإنشاء رابط رمزي...${NC}"
    rm -rf public/storage
    php artisan storage:link
else
    echo -e "${RED}❌ Symbolic Link غير موجود${NC}"
    echo -e "${YELLOW}⚠️  إنشاء Symbolic Link...${NC}"
    php artisan storage:link
fi
echo ""

# 4. التحقق من الصلاحيات
echo -e "${YELLOW}[4/8]${NC} التحقق من الصلاحيات..."
chmod -R 755 storage
chmod -R 755 public/storage
chmod -R 755 bootstrap/cache
echo -e "${GREEN}✅ تم تحديث الصلاحيات${NC}"
echo ""

# 5. التحقق من المجلدات المطلوبة
echo -e "${YELLOW}[5/8]${NC} التحقق من المجلدات المطلوبة..."
REQUIRED_DIRS=("properties" "rooms" "receipts" "ownership_proofs" "contracts" "documents" "images" "images/thumbnails")
for dir in "${REQUIRED_DIRS[@]}"; do
    if [ ! -d "storage/app/public/$dir" ]; then
        echo -e "${YELLOW}⚠️  إنشاء المجلد: storage/app/public/$dir${NC}"
        mkdir -p "storage/app/public/$dir"
    fi
done
echo -e "${GREEN}✅ جميع المجلدات موجودة${NC}"
echo ""

# 6. التحقق من Route
echo -e "${YELLOW}[6/8]${NC} التحقق من Route..."
if grep -q "Route::get('/storage/{path}'" routes/web.php; then
    echo -e "${GREEN}✅ Route للصور موجود${NC}"
else
    echo -e "${RED}❌ Route للصور غير موجود${NC}"
fi
echo ""

# 7. اختبار URL Generation
echo -e "${YELLOW}[7/8]${NC} اختبار إنشاء URLs..."
php artisan tinker --execute="
\$url = \App\Helpers\StorageHelper::url('properties/test.jpg');
echo 'URL Example: ' . \$url . PHP_EOL;
\$appUrl = config('app.url');
echo 'APP_URL: ' . \$appUrl . PHP_EOL;
"
echo ""

# 8. التحقق من وجود ملفات في storage
echo -e "${YELLOW}[8/8]${NC} التحقق من وجود ملفات في storage..."
FILE_COUNT=$(find storage/app/public -type f | wc -l)
if [ "$FILE_COUNT" -gt 0 ]; then
    echo -e "${GREEN}✅ يوجد $FILE_COUNT ملف في storage/app/public${NC}"
    echo -e "${YELLOW}أمثلة على الملفات:${NC}"
    find storage/app/public -type f | head -5
else
    echo -e "${RED}❌ لا توجد ملفات في storage/app/public${NC}"
fi
echo ""

# النتيجة النهائية
echo "=========================================="
echo -e "${GREEN}✅ اكتمل الفحص والإصلاح${NC}"
echo "=========================================="
echo ""
echo "الخطوات التالية:"
echo "1. تأكد من أن APP_URL في .env يحتوي على رابط الموقع الفعلي"
echo "2. إذا لم تظهر الصور بعد، جرب:"
echo "   - فتح Developer Tools (F12) → Network tab"
echo "   - البحث عن طلبات الصور الفاشلة (404)"
echo "   - نسخ URL الصورة ومحاولة فتحها مباشرة"
echo "3. تحقق من logs: tail -f storage/logs/laravel.log"
echo ""

