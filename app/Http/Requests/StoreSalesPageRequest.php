<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'product_name'    => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string', 'max:5000'],
            'features'        => ['required', 'string', 'max:5000'],
            'target_audience' => ['required', 'string', 'max:255'],
            'price'           => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'usp'             => ['nullable', 'string', 'max:1000'],
            'template'        => ['nullable', 'string', 'in:' . implode(',', array_keys(\App\Models\SalesPage::TEMPLATES))],
        ];
    }

    public function messages(): array
    {
        return [
            'features.required' => 'Please list at least one feature (comma- or newline-separated).',
        ];
    }

    /**
     * Normalize the comma/newline-separated feature input into a clean array.
     *
     * @return array<int, string>
     */
    public function featuresArray(): array
    {
        $raw = (string) $this->input('features', '');

        // Split on newlines OR commas, trim, drop empties.
        $parts = preg_split('/[\r\n,]+/', $raw) ?: [];
        $parts = array_map('trim', $parts);

        return array_values(array_filter($parts, fn ($s) => $s !== ''));
    }
}
