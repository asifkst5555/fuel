<?php

namespace App\Http\Requests;

use App\Models\CrowdReport;
use App\Models\Station;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCrowdFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crowd_level' => 'required|in:'.implode(',', [
                CrowdReport::LEVEL_LOW,
                CrowdReport::LEVEL_MEDIUM,
                CrowdReport::LEVEL_HIGH,
                CrowdReport::LEVEL_SEVERE,
            ]),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Station|null $station */
            $station = $this->route('station');

            if (! $station) {
                return;
            }

            $recentDuplicate = CrowdReport::query()
                ->where('station_id', $station->id)
                ->where('ip_address', $this->ip())
                ->where('created_at', '>=', now()->subMinutes(10))
                ->exists();

            if ($recentDuplicate) {
                $validator->errors()->add('crowd_level', 'একই স্টেশনে ১০ মিনিটের মধ্যে একবারই ভিড় আপডেট দেওয়া যাবে।');
            }
        });
    }
}
