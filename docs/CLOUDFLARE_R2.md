# ربط Cloudflare R2 بتطبيق منصة دوميرا

## 1) الغرض من الربط

تخزين ملفات الموقع على Cloudflare R2 بدل القرص المحلي:

- **ملفات المالكين:** إثبات الملكية (PDF/صور)، صور العقارات والغرف، عقود الحجز، إيصالات الدفع.
- **النتيجة:** تحميل أسرع وموزع جغرافياً عبر شبكة Cloudflare، مع إمكانية استخدام CDN لاحقاً.

R2 متوافق مع واجهة S3، لذلك يتم الربط عبر **Laravel Filesystem** مع السائق **s3**.

---

## 2) آلية الربط تقنياً

- **قرص R2:** تم تعريف قرص باسم `r2` في `config/filesystems.php` باستخدام السائق `s3` وإعدادات R2 (`endpoint`, `use_path_style_endpoint`).
- **متغير التبديل:** في `.env` يوجد `FILESYSTEM_DISK_PUBLIC`.
  - قيمته إما `public` (التخزين في `storage/app/public` على السيرفر) أو `r2` (التخزين على Cloudflare R2).
- **الكود لا يفرق بين المحلي و R2:** الكنترولرز و`StorageHelper` و`ImageService` تستخدم قرصاً واحداً من `config('filesystems.public_uploads_disk')`، وهو إما `public` أو `r2` حسب `.env`. نفس منطق الرفع والتحميل يعمل محلياً أو على R2 بدون تغيير الكود.

---

## 3) الإعداد في Cloudflare

1. من لوحة Cloudflare: **R2** → إنشاء **Bucket** لملفات الموقع (مثلاً `domiraa-uploads`).
2. **R2** → **Manage R2 API Tokens** → إنشاء **API Token** بصلاحية **Object Read & Write**.
3. نسخ:
   - **Access Key ID**
   - **Secret Access Key**
   - **Endpoint** (شكله مثل `https://ACCOUNT_ID.r2.cloudflarestorage.com`)
4. لظهور الملفات للزوار: تفعيل **Public access** للـ Bucket أو ربط **Custom Domain**، وأخذ الرابط النهائي (مثل `https://pub-xxx.r2.dev` أو `https://uploads.example.com`) ووضعه في `R2_PUBLIC_URL` في `.env`.

**بيانات Bucket دوميرا (مثال):**
- **Bucket:** `domiraa`
- **S3 API Endpoint:** `https://fc7168b33d5e917d813a2bd6e2d9cca2.r2.cloudflarestorage.com`

---

## 4) الإعداد في المشروع (.env)

```env
# استخدام R2 لرفع الملفات العامة
FILESYSTEM_DISK_PUBLIC=r2

# بيانات R2 (نفس أسماء متغيرات S3)
AWS_ACCESS_KEY_ID=الـ_Access_Key_من_Cloudflare
AWS_SECRET_ACCESS_KEY=الـ_Secret_Key_من_Cloudflare
AWS_DEFAULT_REGION=auto
AWS_BUCKET=domiraa
AWS_ENDPOINT=https://fc7168b33d5e917d813a2bd6e2d9cca2.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true

# رابط الوصول العام للملفات (بدون / في النهاية)
# بعد تفعيل Public access للـ Bucket أو ربط Custom domain من لوحة R2
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

بعد تعديل `.env`:

```bash
php artisan config:clear
# وعند الاستقرار: php artisan config:cache
```

---

## 5) الاعتماديات

التخزين عبر S3/R2 يعتمد على حزمة Laravel الافتراضية. إذا لم تكن مثبتة:

```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

---

## 6) ملخص بجملة واحدة

تم ربط النظام بـ **Cloudflare R2** كقرص تخزين لملفات الموقع (إثبات ملكية، صور عقارات، عقود، إيصالات) باستخدام واجهة S3 في Laravel؛ التبديل بين التخزين المحلي و R2 يتم عبر متغير `.env` فقط (`FILESYSTEM_DISK_PUBLIC=public` أو `r2`) دون تغيير منطق الرفع أو التحقق في الكود.
