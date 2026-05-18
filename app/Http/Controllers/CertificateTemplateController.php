<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateTemplate;

class CertificateTemplateController extends Controller
{
    // GET /template/active
    public function getActive()
    {
        $t = CertificateTemplate::latest()->first();

        if (!$t) {
            return response()->json(['success' => false, 'data' => null]);
        }
        return response()->json([
            'success' => true,
            'data'    => $this->formatTemplate($t),
        ]);
    }

    // GET /template/data
    public function data(Request $request)
    {
        $perPage   = (int) $request->get('per_page', 10);
        $templates = CertificateTemplate::latest()->paginate($perPage);

        $data = $templates->map(fn($t, $i) => array_merge(
            ['no' => ($templates->currentPage() - 1) * $templates->perPage() + $i + 1],
            $this->formatTemplate($t)
        ));

        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'current_page' => $templates->currentPage(),
                'last_page'    => $templates->lastPage(),
                'from'         => $templates->firstItem(),
                'to'           => $templates->lastItem(),
                'total'        => $templates->total(),
            ],
        ]);
    }

    // GET /template/{id}
    public function show($id)
    {
        $t = CertificateTemplate::find($id);

        if (!$t) {
            return response()->json(['success' => false, 'message' => 'Template tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $this->formatTemplate($t)]);
    }

    // POST /template
    public function store(Request $request)
    {
        $request->validate([
            'image'                    => 'required|image|max:5120',
            'width_template'           => 'required|integer',
            'height_template'          => 'required|integer',

            'x_position_name'          => 'required|integer',
            'y_position_name'          => 'required|integer',
            'width_position_name'      => 'required|integer',
            'height_position_name'     => 'required|integer',

            'x_position_cert_number'   => 'required|integer',
            'y_position_cert_number'   => 'required|integer',
            'width_cert_number'        => 'required|integer',
            'height_cert_number'       => 'required|integer',

            'x_position_grade'         => 'required|integer',
            'y_position_grade'         => 'required|integer',
            'width_grade'              => 'required|integer',
            'height_grade'             => 'required|integer',

            'x_position_program_name'  => 'required|integer',
            'y_position_program_name'  => 'required|integer',
            'width_program_name'       => 'required|integer',
            'height_program_name'      => 'required|integer',

            'x_position_publish_date'  => 'required|integer',
            'y_position_publish_date'  => 'required|integer',
            'width_publish_date'       => 'required|integer',
            'height_publish_date'      => 'required|integer',

            'x_position_qr_code'       => 'required|integer',
            'y_position_qr_code'       => 'required|integer',
            'width_qr_code'            => 'required|integer',
            'height_qr_code'           => 'required|integer',
        ]);

        // Sama dengan update — simpan ke public/template/
        $filename = $this->uploadImage($request->file('image'));

        CertificateTemplate::create(array_merge(
            ['path' => 'cert-templates/' . $filename], 
            $request->only([
                'width_template', 'height_template',
                'x_position_name',         'y_position_name',         'width_position_name',     'height_position_name',
                'x_position_cert_number',  'y_position_cert_number',  'width_cert_number',       'height_cert_number',
                'x_position_grade',        'y_position_grade',        'width_grade',             'height_grade',
                'x_position_program_name', 'y_position_program_name', 'width_program_name',      'height_program_name',
                'x_position_publish_date', 'y_position_publish_date', 'width_publish_date',      'height_publish_date',
                'x_position_qr_code',      'y_position_qr_code',      'width_qr_code',           'height_qr_code',
            ])
        ));

        return response()->json(['success' => true, 'message' => 'Template berhasil disimpan.'], 201);
    }

    // PUT /template/{id}  (dikirim via POST + _method=PUT dari JS)
    public function update(Request $request, $id)
    {
        $t = CertificateTemplate::find($id);

        if (!$t) {
            return response()->json(['success' => false, 'message' => 'Template tidak ditemukan.'], 404);
        }

        // ── Validation store() & update() — GANTI yang lama dengan ini:
        $request->validate([
            'image' => 'nullable|image|max:5120',
            'width_template'           => 'required|integer',
            'height_template'          => 'required|integer',

            'x_position_name'          => 'required|integer',
            'y_position_name'          => 'required|integer',
            'width_position_name'      => 'required|integer',
            'height_position_name'     => 'required|integer',

            'x_position_cert_number'   => 'required|integer',
            'y_position_cert_number'   => 'required|integer',
            'width_cert_number'        => 'required|integer',
            'height_cert_number'       => 'required|integer',

            'x_position_grade'         => 'required|integer',
            'y_position_grade'         => 'required|integer',
            'width_grade'              => 'required|integer',
            'height_grade'             => 'required|integer',

            'x_position_program_name'  => 'required|integer',
            'y_position_program_name'  => 'required|integer',
            'width_program_name'       => 'required|integer',
            'height_program_name'      => 'required|integer',

            'x_position_publish_date'  => 'required|integer',
            'y_position_publish_date'  => 'required|integer',
            'width_publish_date'       => 'required|integer',
            'height_publish_date'      => 'required|integer',

            'x_position_qr_code'       => 'required|integer',
            'y_position_qr_code'       => 'required|integer',
            'width_qr_code'            => 'required|integer',
            'height_qr_code'           => 'required|integer',
        ]);

        // ── $request->only() — GANTI yang lama dengan ini (dipakai di store & update):
        // $request->only([
        //     'width_template', 'height_template',
        //     'x_position_name',         'y_position_name',         'width_position_name',     'height_position_name',
        //     'x_position_cert_number',  'y_position_cert_number',  'width_cert_number',       'height_cert_number',
        //     'x_position_grade',        'y_position_grade',        'width_grade',             'height_grade',
        //     'x_position_program_name', 'y_position_program_name', 'width_program_name',      'height_program_name',
        //     'x_position_publish_date', 'y_position_publish_date', 'width_publish_date',      'height_publish_date',
        //     'x_position_qr_code',      'y_position_qr_code',      'width_qr_code',           'height_qr_code',
        // ])

        if ($request->hasFile('image')) {
            // Hapus file lama dulu
            $this->deleteImage($t->path);

            $filename = $this->uploadImage($request->file('image'));
            $t->path = 'cert-templates/' . $filename; 
        }

        $t->fill($request->only([
            'width_template', 'height_template',
            'x_position_name',         'y_position_name',         'width_position_name',     'height_position_name',
            'x_position_cert_number',  'y_position_cert_number',  'width_cert_number',       'height_cert_number',
            'x_position_grade',        'y_position_grade',        'width_grade',             'height_grade',
            'x_position_program_name', 'y_position_program_name', 'width_program_name',      'height_program_name',
            'x_position_publish_date', 'y_position_publish_date', 'width_publish_date',      'height_publish_date',
            'x_position_qr_code',      'y_position_qr_code',      'width_qr_code',           'height_qr_code',
        ]))->save();

        return response()->json(['success' => true, 'message' => 'Template berhasil diperbarui.']);
    }

    // DELETE /template/{id}
    public function destroy($id)
    {
        $t = CertificateTemplate::find($id);

        if (!$t) {
            return response()->json(['success' => false, 'message' => 'Template tidak ditemukan.'], 404);
        }

        $this->deleteImage($t->path);
        $t->delete();

        return response()->json(['success' => true, 'message' => 'Template berhasil dihapus.']);
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function uploadImage($file): string
    {
        $isProduction = app()->environment('production');
        $folder = $isProduction ? '/home/cery9751/public_html/v/qrcode' : public_path('v/qrcode'); 

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = 'template_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);

        return $filename;
    }

    private function deleteImage(?string $path): void
    {
        $isProduction = app()->environment('production');
        $fullPath = $isProduction ? '/home/cery9751/public_html/v/qrcode' : public_path($path); 
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Format output.
     * path DB  : "template/template_abc123.jpg"
     * URL hasil: "https://domain.com/template/template_abc123.jpg"
     */
    private function formatTemplate(CertificateTemplate $t): array
    {
        return [
            'id'                       => $t->id,
            'path'                     => $t->path ? url($t->path) : null,
            'width_template'           => $t->width_template,
            'height_template'          => $t->height_template,
            'created_at'               => $t->created_at->format('d M Y'),

            'x_position_name'          => $t->x_position_name,
            'y_position_name'          => $t->y_position_name,
            'width_position_name'      => $t->width_position_name,
            'height_position_name'     => $t->height_position_name,

            'x_position_cert_number'   => $t->x_position_cert_number,
            'y_position_cert_number'   => $t->y_position_cert_number,
            'width_cert_number'        => $t->width_cert_number,
            'height_cert_number'       => $t->height_cert_number,

            'x_position_grade'         => $t->x_position_grade,
            'y_position_grade'         => $t->y_position_grade,
            'width_grade'              => $t->width_grade,
            'height_grade'             => $t->height_grade,

            'x_position_program_name'  => $t->x_position_program_name,
            'y_position_program_name'  => $t->y_position_program_name,
            'width_program_name'       => $t->width_program_name,
            'height_program_name'      => $t->height_program_name,

            'x_position_publish_date'  => $t->x_position_publish_date,
            'y_position_publish_date'  => $t->y_position_publish_date,
            'width_publish_date'       => $t->width_publish_date,
            'height_publish_date'      => $t->height_publish_date,

            'x_position_qr_code'       => $t->x_position_qr_code,
            'y_position_qr_code'       => $t->y_position_qr_code,
            'width_qr_code'            => $t->width_qr_code,
            'height_qr_code'           => $t->height_qr_code,
        ];
    }
}