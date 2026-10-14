<div style="text-align:center;padding:10px 0;">
    <div style="display:inline-flex;background:rgba(229,229,234,.8);border-radius:10px;overflow:hidden;">
        <a href="{{ url()->current() }}" style="padding:7px 16px;font-size:.75rem;font-weight:600;text-decoration:none;{{ request('theme') !== 'mobile' ? 'background:#16a085;color:#fff;border-radius:10px;' : 'color:#8e8e93;' }}"><i class="fas fa-desktop" style="margin-right:4px;"></i>PC</a>
        <a href="{{ url()->current() }}?theme=mobile" style="padding:7px 16px;font-size:.75rem;font-weight:600;text-decoration:none;{{ request('theme') === 'mobile' ? 'background:#16a085;color:#fff;border-radius:10px;' : 'color:#8e8e93;' }}"><i class="fas fa-mobile-alt" style="margin-right:4px;"></i>Mobile</a>
    </div>
</div>
