@extends('layouts.admin')
@section('title', 'New Campaign')

@section('breadcrumb')
    <a href="{{ route('admin.newsletter.campaigns') }}">Newsletter</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">New Campaign</span>
@endsection

@section('content')

    <form action="{{ route('admin.newsletter.send') }}" method="POST">
        @csrf

        <div class="page-header">
            <div>
                <h1 class="page-title">New Email Campaign</h1>
                <p class="page-subtitle">Compose and send to all active subscribers</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.newsletter.campaigns') }}" class="btn btn--ghost">Cancel</a>
                <button type="submit" class="btn btn--secondary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Draft
                </button>
            </div>
        </div>

        <div class="form-layout form-layout--wide">

            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="card">
                    <div class="card-header"><span class="card-title">Email Details</span></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">

                        <div class="form-group">
                            <label class="form-label" for="subject">Subject Line <span class="required">*</span></label>
                            <input type="text" id="subject" name="subject"
                                class="form-control {{ $errors->has('subject') ? 'form-control--error' : '' }}"
                                value="{{ old('subject') }}" placeholder="e.g. New Caterpillar Parts Just Arrived" required>
                            @error('subject')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="preview_text">Preview Text</label>
                            <input type="text" id="preview_text" name="preview_text" class="form-control" maxlength="255"
                                value="{{ old('preview_text') }}"
                                placeholder="Short text shown in inbox preview (optional)">
                            <span class="form-hint">Shown after the subject line in most email clients</span>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">Email Body</span></div>
                    <div class="rich-editor-wrap" data-rich-editor="body_html_input"
                        style="border:none;border-radius:0 0 var(--radius-lg) var(--radius-lg);">
                        <div class="rich-editor-toolbar">
                            <button data-cmd="bold"><i class="fa-solid fa-bold"></i></button>
                            <button data-cmd="italic"><i class="fa-solid fa-italic"></i></button>
                            <button data-cmd="underline"><i class="fa-solid fa-underline"></i></button>
                            <button data-cmd="formatBlock" data-arg="h2">H2</button>
                            <button data-cmd="formatBlock" data-arg="h3">H3</button>
                            <button data-cmd="insertUnorderedList"><i class="fa-solid fa-list-ul"></i></button>
                            <button data-cmd="insertOrderedList"><i class="fa-solid fa-list-ol"></i></button>
                            <button data-cmd="createLink"
                                onclick="event.preventDefault();const u=prompt('URL:');if(u)document.execCommand('createLink',false,u)">
                                <i class="fa-solid fa-link"></i>
                            </button>
                            <button data-cmd="removeFormat"><i class="fa-solid fa-text-slash"></i></button>
                        </div>
                        <div class="rich-editor-content" contenteditable="true" style="min-height:400px;">
                            {{ old('body_html') }}</div>
                    </div>
                    <input type="hidden" name="body_html" id="body_html_input" value="{{ old('body_html') }}">
                    @error('body_html')
                        <span class="form-error" style="padding:8px 16px;">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">

                <div class="form-sidebar-card">
                    <div class="form-sidebar-section">
                        <div class="form-sidebar-title">Send Settings</div>
                        <div
                            style="background:var(--info-pale);border:1px solid #BFDBFE;border-radius:var(--radius);padding:12px;font-size:12px;color:var(--info);line-height:1.6;margin-bottom:12px;">
                            <i class="fa-solid fa-circle-info"></i>
                            This campaign will be sent to <strong>all active subscribers</strong>.
                            Make sure to preview before sending.
                        </div>
                        <button type="submit" class="btn btn--secondary w-full"
                            style="justify-content:center;margin-bottom:8px;">
                            <i class="fa-solid fa-floppy-disk"></i> Save as Draft
                        </button>
                        <div style="font-size:11px;color:var(--text-muted);text-align:center;">
                            You can send from the draft preview page
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card" style="padding:16px;">
                    <div style="font-size:12px;line-height:1.8;color:var(--text-muted);">
                        <div style="font-weight:600;color:var(--text-base);margin-bottom:8px;">
                            <i class="fa-solid fa-lightbulb" style="color:var(--warning);"></i> Writing Tips
                        </div>
                        <ul style="padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                            <li>Keep subject lines under 50 characters</li>
                            <li>Include a clear call-to-action button</li>
                            <li>Personalize with part categories customers care about</li>
                            <li>Always include an unsubscribe option (added automatically)</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </form>

@endsection
