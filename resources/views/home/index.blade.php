@extends('layouts.public')

@php
    $bnNumber = fn ($value) => strtr((string) $value, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
    $totalStations = $stations->count();
    $availableCount = $stations->filter(fn ($station) => $station->fuelStatus && ($station->fuelStatus->octane || $station->fuelStatus->diesel))->count();
    $updatedCount = $stations->filter(fn ($station) => $station->fuelStatus?->updated_at)->count();
    $params = request()->query();
    $feedParams = request()->only(['search', 'availability', 'crowd', 'sort']);
@endphp

@section('content')
    <div class="container-shell">
        @if (session('crowd_feedback_status'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mt-4 mb-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('crowd_feedback_status') }}
            </div>
        @endif

        <section class="hero-shell">
            <div class="hero-panel">
                <div class="hero-grid">
                    <div>
                        <div class="hero-kicker">
                            <i class="bi bi-activity"></i>
                            হাটহাজারী উপজেলার লাইভ ফুয়েল মনিটরিং
                        </div>

                        <h1 class="hero-title">অকটেন ও ডিজেলের বর্তমান সরবরাহ এক নজরে দেখুন</h1>
                        <p class="hero-copy">
                            হাটহাজারীর স্টেশনভিত্তিক জ্বালানি পরিস্থিতি, সর্বশেষ আপডেট এবং জনসাধারণের লাইনের তথ্য একটি পরিচ্ছন্ন ও দ্রুত আপডেট হওয়া ইন্টারফেসে দেখা যাচ্ছে।
                        </p>

                        <div class="hero-stats">
                            <div class="stat-card">
                                <div class="stat-label">মোট স্টেশন</div>
                                <div class="stat-value bn-numeral">{{ $bnNumber($totalStations) }}</div>
                                <div class="stat-meta">বর্তমানে ট্র্যাক করা হচ্ছে</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">সক্রিয় সরবরাহ</div>
                                <div class="stat-value bn-numeral" style="color: var(--success);">{{ $bnNumber($availableCount) }}</div>
                                <div class="stat-meta">অকটেন বা ডিজেল পাওয়া যাচ্ছে</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">আপডেটেড রেকর্ড</div>
                                <div class="stat-value bn-numeral" style="color: var(--primary);">{{ $bnNumber($updatedCount) }}</div>
                                <div class="stat-meta">সর্বশেষ ডেটা পাওয়া গেছে</div>
                            </div>
                        </div>
                    </div>

                    <aside class="search-panel">
                        <div class="panel-label">দ্রুত অনুসন্ধান</div>
                        <div class="panel-title">স্টেশন খুঁজুন</div>
                        <div class="panel-copy">স্টেশনের নাম বা লোকেশন দিয়ে দ্রুত স্ট্যাটাস দেখুন। এই তালিকা প্রতি ১৫ সেকেন্ডে স্বয়ংক্রিয়ভাবে রিফ্রেশ হয়।</div>

                        <form method="GET" action="{{ route('home') }}" class="search-form">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    class="form-control border-start-0"
                                    placeholder="স্টেশন নাম বা লোকেশন লিখুন"
                                >
                                <button class="btn btn-primary" type="submit">অনুসন্ধান</button>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <div class="refresh-note">
                                <span class="live-dot"></span>
                                প্রতি ১৫ সেকেন্ডে রিফ্রেশ
                            </div>
                            <div class="refresh-note">
                                <i class="bi bi-shield-check"></i>
                                স্থানীয় মনিটরিং ডেটা
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="section-header">
                <div>
                    <h2 class="section-title">হাটহাজারী ফুয়েল স্টেশন তালিকা</h2>
                    <p class="section-copy">বর্তমান জ্বালানি সরবরাহ, জনসাধারণের লাইনের অবস্থা এবং সর্বশেষ আপডেট সময় এখানে দেখানো হচ্ছে।</p>
                </div>
                <div class="refresh-note">
                    <span class="live-dot"></span>
                    লাইভ আপডেট সক্রিয়
                </div>
            </div>

            <div class="filter-shell">
                <div class="filter-title">স্মার্ট ফিল্টার ও সাজানো</div>

                <form method="GET" action="{{ route('home') }}" class="mobile-filter-panel">
                    <input type="hidden" name="search" value="{{ $search }}">

                    <div class="mobile-filter-grid">
                        <div class="mobile-filter-field">
                            <label for="availability_mobile">জ্বালানি</label>
                            <select id="availability_mobile" name="availability" class="form-select">
                                <option value="" @selected(empty($availability))>সব স্টেশন</option>
                                <option value="octane" @selected($availability === 'octane')>শুধু অকটেন</option>
                                <option value="diesel" @selected($availability === 'diesel')>শুধু ডিজেল</option>
                                <option value="both" @selected($availability === 'both')>দুইটিই আছে</option>
                            </select>
                        </div>

                        <div class="mobile-filter-field">
                            <label for="crowd_mobile">ভিড়</label>
                            <select id="crowd_mobile" name="crowd" class="form-select">
                                <option value="" @selected(empty($crowd))>সব অবস্থা</option>
                                <option value="low" @selected($crowd === 'low')>ভিড় কম</option>
                                <option value="high" @selected($crowd === 'high')>ভিড় বেশি</option>
                            </select>
                        </div>

                        <div class="mobile-filter-field">
                            <label for="sort_mobile">সাজানো</label>
                            <select id="sort_mobile" name="sort" class="form-select">
                                <option value="name" @selected(empty($sort) || $sort === 'name')>নাম অনুযায়ী</option>
                                <option value="updated" @selected($sort === 'updated')>নতুন আপডেট আগে</option>
                            </select>
                        </div>
                    </div>

                    <div class="mobile-filter-actions">
                        <button type="submit" class="btn btn-primary">ফিল্টার প্রয়োগ</button>
                        <a href="{{ route('home', ['search' => $search]) }}" class="btn btn-outline-secondary">রিসেট</a>
                    </div>
                </form>

                <div class="filter-bar desktop-filter-bar">
                    <a href="{{ route('home', array_merge($params, ['availability' => null])) }}" class="filter-chip {{ empty($availability) ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap-fill"></i> সব স্টেশন
                    </a>
                    <a href="{{ route('home', array_merge($params, ['availability' => 'octane'])) }}" class="filter-chip {{ $availability === 'octane' ? 'active' : '' }}">
                        <i class="bi bi-droplet-fill"></i> শুধু অকটেন
                    </a>
                    <a href="{{ route('home', array_merge($params, ['availability' => 'diesel'])) }}" class="filter-chip {{ $availability === 'diesel' ? 'active' : '' }}">
                        <i class="bi bi-fuel-pump-diesel-fill"></i> শুধু ডিজেল
                    </a>
                    <a href="{{ route('home', array_merge($params, ['availability' => 'both'])) }}" class="filter-chip {{ $availability === 'both' ? 'active' : '' }}">
                        <i class="bi bi-check2-circle"></i> দুইটিই আছে
                    </a>
                    <a href="{{ route('home', array_merge($params, ['crowd' => 'low'])) }}" class="filter-chip {{ $crowd === 'low' ? 'active' : '' }}">
                        <i class="bi bi-bicycle"></i> ভিড় কম
                    </a>
                    <a href="{{ route('home', array_merge($params, ['crowd' => 'high'])) }}" class="filter-chip {{ $crowd === 'high' ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> ভিড় বেশি
                    </a>
                    <a href="{{ route('home', array_merge($params, ['sort' => 'updated'])) }}" class="filter-chip {{ $sort === 'updated' ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> নতুন আপডেট আগে
                    </a>
                    <a href="{{ route('home', array_merge($params, ['sort' => 'name'])) }}" class="filter-chip {{ empty($sort) || $sort === 'name' ? 'active' : '' }}">
                        <i class="bi bi-sort-alpha-down"></i> নাম অনুযায়ী
                    </a>
                </div>
            </div>

            <div id="station-cards" data-feed-url="{{ route('stations.feed', $feedParams) }}" data-current-search="{{ $search }}">
                @include('home.partials.station-cards', ['stations' => $stations])
            </div>
        </section>

        <section class="section-block mb-5">
            <div class="footer-panel">
                <div class="hero-kicker justify-content-center d-inline-flex mb-3">তথ্য তদারকিতে</div>
                <h3 class="section-title">হাটহাজারী ফুয়েল মনিটরিং কন্ট্রোল সেল</h3>
                <p class="section-copy mt-2">হাটহাজারী উপজেলার স্থানীয় মনিটরিং, স্টেশন আপডেট এবং জনসাধারণের ফিডব্যাক সমন্বয়ে পরিচালিত।</p>

                <div class="info-strip">
                    <div class="info-chip"><i class="bi bi-shield-check text-success"></i> নির্ভরযোগ্য ডেটা</div>
                    <div class="info-chip"><i class="bi bi-phone text-primary"></i> মোবাইল ফ্রেন্ডলি</div>
                    <div class="info-chip"><i class="bi bi-lightning-charge text-warning"></i> দ্রুত রিফ্রেশ</div>
                </div>

                <div class="notice-box">
                    এই পেইজে দেখানো তথ্য স্থানীয় আপডেট ও স্টেশনভিত্তিক পর্যবেক্ষণের ভিত্তিতে প্রকাশ করা হয়। সরবরাহ পরিস্থিতি যেকোনো সময় পরিবর্তিত হতে পারে, তাই প্রয়োজন হলে স্টেশনের সঙ্গে সরাসরি যোগাযোগ করা উত্তম।
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const cardsContainer = document.getElementById('station-cards');

            if (!cardsContainer) {
                return;
            }

            const refresh = async () => {
                try {
                    const response = await fetch(cardsContainer.dataset.feedUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    cardsContainer.innerHTML = await response.text();
                } catch (error) {
                    console.error('Station auto-refresh failed.', error);
                }
            };

            window.setInterval(refresh, 15000);
        })();
    </script>
@endpush
