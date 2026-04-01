@php
    use App\Models\CrowdReport;

    $bnNumber = fn ($value) => strtr((string) $value, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
    $crowdMap = [
        CrowdReport::LEVEL_LOW => [
            'label' => 'ভিড় নেই (০-৩০ গাড়ি)',
            'class' => 'text-success',
            'icon' => 'bi-bicycle',
        ],
        CrowdReport::LEVEL_MEDIUM => [
            'label' => 'মাঝারি ভিড় (৩০-৮০ গাড়ি)',
            'class' => 'text-warning',
            'icon' => 'bi-car-front-fill',
        ],
        CrowdReport::LEVEL_HIGH => [
            'label' => 'প্রচুর ভিড় (১২০+ গাড়ি)',
            'class' => 'text-danger',
            'icon' => 'bi-people-fill',
        ],
        CrowdReport::LEVEL_SEVERE => [
            'label' => 'চাপ অত্যন্ত বেশি (২৫০+ গাড়ি)',
            'class' => 'text-danger',
            'icon' => 'bi-exclamation-triangle-fill',
        ],
    ];
@endphp

<div class="station-grid">
    @forelse ($stations as $station)
        @php
            $status = $station->fuelStatus;
            $crowd = $station->latestCrowdReport;
            $hasAnyFuel = $status && ($status->octane || $status->petrol || $status->diesel);
            $statusLabel = $hasAnyFuel ? 'সরবরাহ চালু' : 'সরবরাহ বন্ধ';
            $statusClass = $hasAnyFuel ? 'status-available' : 'status-unavailable';
            $octaneText = $status?->octane ? 'আছে' : 'নেই';
            $dieselText = $status?->diesel ? 'আছে' : 'নেই';
            $crowdInfo = $crowd && isset($crowdMap[$crowd->crowd_level]) ? $crowdMap[$crowd->crowd_level] : null;
        @endphp

        <article class="station-card">
            <div class="station-head">
                <div class="station-top">
                    <div class="station-identity">
                        <div class="station-icon">
                            <i class="bi bi-fuel-pump-fill"></i>
                        </div>
                        <div>
                            <h3 class="station-name">{{ $station->name }}</h3>
                            <div class="station-location">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>{{ $station->location }}</span>
                            </div>
                        </div>
                    </div>

                    <span class="status-badge {{ $statusClass }}">
                        <i class="bi {{ $hasAnyFuel ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <div class="station-body">
                <div class="status-grid">
                    <div class="status-box">
                        <div class="status-label">&#x0985;&#x0995;&#x099F;&#x09C7;&#x09A8;</div>
                        <div class="status-value {{ $status?->octane ? 'available' : 'unavailable' }}">{{ $octaneText }}</div>
                    </div>
                    <div class="status-box">
                        <div class="status-label">&#x09AA;&#x09C7;&#x099F;&#x09CD;&#x09B0;&#x09CB;&#x09B2;</div>
                        <div class="status-value {{ $status?->petrol ? 'available' : 'unavailable' }}">{{ $status?->petrol ? $octaneText : $dieselText }}</div>
                    </div>
                    <div class="status-box">
                        <div class="status-label">&#x09A1;&#x09BF;&#x099C;&#x09C7;&#x09B2;</div>
                        <div class="status-value {{ $status?->diesel ? 'available' : 'unavailable' }}">{{ $dieselText }}</div>
                    </div>
                </div>

                <div class="detail-list">
                    <div class="detail-row detail-card-supply">
                        <div class="detail-icon detail-icon-supply">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <div>
                            <div class="detail-title">বর্তমান সরবরাহ</div>
                            <div class="detail-value">Octane {{ $octaneText }}, Petrol {{ $status?->petrol ? $octaneText : $dieselText }}, Diesel {{ $dieselText }}.</div>
                        </div>
                    </div>

                    <div class="detail-row detail-card-dealer">
                        <div class="detail-icon detail-icon-dealer">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <div class="detail-title">&#x09A1;&#x09BF;&#x09B2;&#x09BE;&#x09B0;</div>
                            <div class="detail-value">&#x09A1;&#x09BF;&#x09B2;&#x09BE;&#x09B0;: {{ $station->dealer ?? 'No info' }}</div>
                            <div class="detail-meta">Station dealer info</div>
                        </div>
                    </div>

                    <div class="detail-row detail-card-crowd">
                        <div class="detail-icon detail-icon-crowd">
                            <i class="bi {{ $crowdInfo['icon'] ?? 'bi-people-fill' }}"></i>
                        </div>
                        <div>
                            <div class="detail-title">বর্তমান লাইনের অবস্থা</div>
                            <div class="detail-value {{ $crowdInfo['class'] ?? 'text-secondary' }}">{{ $crowdInfo['label'] ?? 'এখনও কোনো পাবলিক ভিড় রিপোর্ট আসেনি' }}</div>
                            <div class="detail-meta">{{ $crowd?->updated_at?->locale('bn')->diffForHumans() ?? 'আপনার আপডেটের অপেক্ষায়' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="station-foot">
                <div class="station-meta">
                    <div class="meta-list">
                        <span class="meta-chip"><i class="bi bi-clock-history"></i> {{ $status?->updated_at?->format('h:i A') ?? 'N/A' }}</span>
                        <span class="meta-chip"><i class="bi bi-person-badge"></i> {{ $station->dealer ?? 'Dealer unavailable' }}</span>
                        <span class="meta-chip">স্টেশন আইডি: <span class="bn-numeral">{{ $bnNumber($station->id) }}</span></span>
                    </div>

                    <button
                        type="button"
                        class="feedback-button btn"
                        data-bs-toggle="modal"
                        data-bs-target="#crowdFeedbackModal-{{ $station->id }}"
                    >
                        <i class="bi bi-megaphone-fill"></i>
                        ভিড় জানান
                    </button>
                </div>
            </div>
        </article>

        <div class="modal fade" id="crowdFeedbackModal-{{ $station->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered crowd-modal-dialog">
                <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="modal-header border-0 pb-0 px-4 pt-4">
                        <div>
                            <h5 class="modal-title fw-bold fs-3 mb-1">পাম্পে ভিড় কেমন?</h5>
                            <div class="text-primary fw-bold">{{ $station->name }}</div>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4 pb-4">
                        <div class="alert alert-primary rounded-4 border-0 py-3 mb-4">
                            <i class="bi bi-heart-fill text-danger me-2"></i>
                            সঠিক তথ্য দিলে অন্যরা লাইনের অবস্থা দ্রুত বুঝতে পারবে।
                        </div>

                        <form method="POST" action="{{ route('stations.crowd-feedback', $station) }}" class="d-grid gap-3">
                            @csrf
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <button type="submit" name="crowd_level" value="{{ CrowdReport::LEVEL_LOW }}" class="btn text-start p-4 rounded-4 border border-success-subtle bg-white crowd-modal-option">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="detail-icon success">
                                        <i class="bi bi-bicycle"></i>
                                    </div>
                                    <div class="fw-bold fs-5 text-success">ভিড় নেই (০-৩০ গাড়ি)</div>
                                </div>
                            </button>

                            <button type="submit" name="crowd_level" value="{{ CrowdReport::LEVEL_MEDIUM }}" class="btn text-start p-4 rounded-4 border border-warning-subtle bg-white crowd-modal-option">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="detail-icon warning">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>
                                    <div class="fw-bold fs-5 text-warning-emphasis">মাঝারি ভিড় (৩০-৮০ গাড়ি)</div>
                                </div>
                            </button>

                            <button type="submit" name="crowd_level" value="{{ CrowdReport::LEVEL_HIGH }}" class="btn text-start p-4 rounded-4 border border-danger-subtle bg-white crowd-modal-option">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="detail-icon" style="background:#fef2f2;color:#dc2626;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="fw-bold fs-5 text-danger">প্রচুর ভিড় (১২০+ গাড়ি)</div>
                                </div>
                            </button>

                            <button type="submit" name="crowd_level" value="{{ CrowdReport::LEVEL_SEVERE }}" class="btn text-start p-4 rounded-4 border border-danger-subtle bg-white crowd-modal-option">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="detail-icon" style="background:#fef2f2;color:#dc2626;">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="fw-bold fs-5 text-danger">চাপ অত্যন্ত বেশি (২৫০+ গাড়ি)</div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="mb-3" style="font-size: 2.75rem; color: #94a3b8;">
                <i class="bi bi-fuel-pump"></i>
            </div>
            <h3 class="section-title mb-2">কোনো স্টেশন পাওয়া যায়নি</h3>
            <p class="section-copy mb-0">বর্তমান ফিল্টার বা সার্চে কোনো স্টেশন মেলেনি। অন্য ফিল্টার চেষ্টা করুন অথবা কিছুক্ষণ পরে আবার দেখুন।</p>
        </div>
    @endforelse
</div>
