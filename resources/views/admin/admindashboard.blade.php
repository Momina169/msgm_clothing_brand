<x-app-layout>
    <x-slot name="header">
        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h3>
    </x-slot>

    <div class="container py-4">

        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center">
                    <label for="month_filter" class="form-label me-2 mb-0">Select Month:</label>
                    <select name="month" id="month_filter" class="form-select w-auto me-2" onchange="this.form.submit()">
                        
                        <option value="" {{ request('month') == '' ? 'selected' : '' }}>Current Month Sales Shown</option>
                        @php
                            $currentDate = \Carbon\Carbon::now();
                            $currentMonthYearValue = $currentDate->month . '-' . $currentDate->year;

                            // Loop for the last 12 months
                            for ($i = 0; $i < 12; $i++) {
                                $date = \Carbon\Carbon::now()->subMonths($i);
                                $monthNum = $date->month;
                                $yearNum = $date->year;
                                $monthName = $date->format('F Y');
                                $value = $monthNum . '-' . $yearNum; 

                                
                                $isSelected = (request('month', $currentMonthYearValue) == $value) ? 'selected' : '';
                                echo "<option value='{$value}' {$isSelected}>{$monthName}</option>";
                            }
                        @endphp
                    </select>
                    <noscript><button type="submit" class="btn btn-secondary">Filter</button></noscript>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Total Sales Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Sales ({{ $selectedDateForDisplay->format('F Y') }})
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    PKR. {{ number_format($totalSales, 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order pending --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-start border-success border-4">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Orders Pending
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $ordersPendingCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- new users -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-start border-dark border-4">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    New Users  ({{ $selectedDateForDisplay->format('F Y') }})
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $newUsersThisMonthCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
