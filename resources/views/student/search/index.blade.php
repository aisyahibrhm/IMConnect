@extends('layouts.app')
@section('title', 'Find Alumni — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="page-header">
        <h1><i class="fas fa-search"></i> Find Alumni</h1>
        <p>Search and filter KPMIM alumni by course, industry, or job field.</p>
    </div>

    {{-- Search & Filter form --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-body">
            <form method="GET" action="{{ route('student.alumni.index') }}" id="searchForm">
                <div style="display:grid; grid-template-columns:1fr auto; gap:12px; margin-bottom:14px;">
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by name, job title, industry or company..."
                           style="font-size:15px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
                    <div style="flex:1; min-width:180px;">
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted);
                                      text-transform:uppercase; letter-spacing:0.4px; margin-bottom:5px;">
                            Course
                        </label>
                        <select name="course_id" onchange="document.getElementById('searchForm').submit()">
                            <option value="">All courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex:1; min-width:180px;">
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted);
                                      text-transform:uppercase; letter-spacing:0.4px; margin-bottom:5px;">
                            Industry
                        </label>
                        <select name="industry" onchange="document.getElementById('searchForm').submit()">
                            <option value="">All industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}"
                                    {{ request('industry') === $industry ? 'selected' : '' }}>
                                    {{ $industry }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('search') || request('course_id') || request('industry'))
                        <a href="{{ route('student.alumni.index') }}"
                           class="btn btn-ghost" style="align-self:flex-end;">
                            <i class="fas fa-times"></i> Clear filters
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Results summary --}}
    @if(request('search') || request('course_id') || request('industry'))
        <p style="font-size:13.5px; color:var(--text-secondary); margin-bottom:18px;">
            <i class="fas fa-list"></i>
            Showing <strong>{{ $alumni->total() }}</strong> result(s)
            @if(request('search')) for &ldquo;<strong>{{ request('search') }}</strong>&rdquo; @endif
        </p>
    @endif

    {{-- Alumni grid --}}
    @if($alumni->isEmpty())
        <div class="card">
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <h3>No alumni found</h3>
                <p>Try different keywords or remove some filters to see more results.</p>
                <a href="{{ route('student.alumni.index') }}" class="btn btn-outline" style="margin-top:14px;">
                    <i class="fas fa-times"></i> Clear all filters
                </a>
            </div>
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
            @foreach($alumni as $alum)
                <a href="{{ route('student.alumni.show', $alum) }}" class="alumni-card">
                    <div class="alumni-card-header">
                        <div class="avatar">
                            {{ strtoupper(substr($alum->user->name, 0, 2)) }}
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="alumni-card-name" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $alum->user->name }}
                            </div>
                            <div class="alumni-card-meta">
                                <i class="fas fa-briefcase" style="font-size:11px;"></i>
                                {{ $alum->job_position ?? 'Position not set' }}
                            </div>
                            @if($alum->company)
                                <div class="alumni-card-meta" style="margin-top:2px;">
                                    <i class="fas fa-building" style="font-size:11px;"></i>
                                    {{ $alum->company }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="alumni-card-tags">
                        @if($alum->industry)
                            <span class="badge badge-info">{{ $alum->industry }}</span>
                        @endif
                        <span class="badge" style="background:var(--crimson-subtle);
                                                   color:var(--crimson);
                                                   border:1px solid var(--crimson-muted);">
                            {{ $alum->course->code }}
                        </span>
                        @if($alum->graduation_year)
                            <span class="badge" style="background:var(--surface-2);
                                                       color:var(--text-secondary);
                                                       border:1px solid var(--border);">
                                Class of {{ $alum->graduation_year }}
                            </span>
                        @endif
                    </div>

                    <div class="alumni-card-social">
                        @if($alum->linkedin_url)
                            <span class="social-icon linkedin" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </span>
                        @endif
                        @if($alum->instagram_url)
                            <span class="social-icon instagram" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </span>
                        @endif
                        <span style="margin-left:auto; font-size:12.5px; color:var(--crimson);
                                     font-weight:600; display:flex; align-items:center; gap:4px;">
                            View profile <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination-wrapper" style="margin-top:24px;">
            {{ $alumni->links() }}
        </div>
    @endif

</div>
@endsection