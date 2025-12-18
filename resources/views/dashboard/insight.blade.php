<div>
    <canvas id="confetti-canvas"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; pointer-events: none;">
    </canvas>

    @include('dashboard.inc.sum_rank')
    @include('dashboard.inc.sum_attd')
    @include('dashboard.inc.sum_top')
    @include('dashboard.inc.top_late_alpa')

    @include('dashboard.inc.insight_inc')
</div>
