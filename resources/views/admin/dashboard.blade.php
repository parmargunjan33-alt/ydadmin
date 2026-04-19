@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back! Here\'s your system overview.')

@section('content')
    <div class="row">
        <!-- Stat Cards -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-value">{{ $stats['active_subscriptions'] }}</div>
                <div class="stat-label">Active Subscriptions</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="stat-value">₹{{ number_format($stats['total_revenue'], 0) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value">{{ $stats['total_universities'] }}</div>
                <div class="stat-label">Universities</div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="row">
        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon blue" style="font-size: 24px;">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['total_courses'] }}</div>
                <div class="stat-label">Courses</div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon green" style="font-size: 24px;">
                    <i class="bi bi-calendar-range"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['total_semesters'] }}</div>
                <div class="stat-label">Semesters</div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon orange" style="font-size: 24px;">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['total_subjects'] }}</div>
                <div class="stat-label">Subjects</div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon red" style="font-size: 24px;">
                    <i class="bi bi-file-pdf"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['total_pdfs'] }}</div>
                <div class="stat-label">PDF Files</div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon blue" style="font-size: 24px;">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['active_users'] }}</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon green" style="font-size: 24px;">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-value" style="font-size: 24px;">{{ $stats['total_subscriptions'] }}</div>
                <div class="stat-label">All Subscriptions</div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Recent Subscriptions -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-credit-card"></i> Recent Subscriptions
                </div>
                <div class="card-body">
                    @if ($recent_subscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Semester</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_subscriptions as $sub)
                                        <tr>
                                            <td>
                                                <strong>{{ $sub->user->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $sub->user->mobile ?? 'N/A' }}</small>
                                            </td>
                                            <td>{{ $sub->semester->label ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($sub->amount, 2) }}</td>
                                            <td>
                                                @if ($sub->status === 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif ($sub->status === 'expired')
                                                    <span class="badge badge-danger">Expired</span>
                                                @else
                                                    <span class="badge badge-warning">{{ ucfirst($sub->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $sub->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p>No subscriptions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-people"></i> Recent Users
                </div>
                <div class="card-body">
                    @if ($recent_users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>University</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->university->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($user->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p>No users yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Universities -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-building"></i> Top Universities (by User Count)
                </div>
                <div class="card-body">
                    @if ($top_universities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>University</th>
                                        <th>City</th>
                                        <th>Users</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_universities as $uni)
                                        <tr>
                                            <td><strong>{{ $uni->name }}</strong></td>
                                            <td>{{ $uni->city }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $uni->users_count }}</span>
                                            </td>
                                            <td>
                                                @if ($uni->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p>No universities yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subscription Status Breakdown -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-chart-pie"></i> Subscription Status Breakdown
                </div>
                <div class="card-body">
                    @if ($subscription_status->count() > 0)
                        <div style="padding: 20px;">
                            @foreach ($subscription_status as $status => $count)
                                <div style="margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <strong>{{ ucfirst($status) }}</strong>
                                        <span>{{ $count }}</span>
                                    </div>
                                    <div style="width: 100%; background-color: #e0e0e0; border-radius: 4px; height: 20px; overflow: hidden;">
                                        @php
                                            $total = $subscription_status->sum();
                                            $percentage = ($count / $total) * 100;
                                            $colors = [
                                                'active' => '#4caf50',
                                                'expired' => '#f44336',
                                                'pending' => '#ff9800',
                                            ];
                                            $color = $colors[$status] ?? '#667eea';
                                        @endphp
                                        <div style="width: {{ $percentage }}%; background-color: {{ $color }}; height: 100%; transition: all 0.3s ease;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p>No subscription data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection