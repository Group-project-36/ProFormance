@if(!request()->cookie('edu_notice_accepted'))
<div id="eduBanner" class="edu-banner" role="dialog" aria-live="polite">
    <span>⚠ This website is not real. It was created for educational purposes only.</span>

    <button type="button" id="eduBannerBtn" class="edu-banner__btn">
        I Understand
    </button>
</div>
@endif