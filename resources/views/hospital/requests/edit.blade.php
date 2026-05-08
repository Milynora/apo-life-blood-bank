<x-app-layout title="Edit Blood Request">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('hospital.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('hospital.requests.index') }}">Requests</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('hospital.requests.show', $bloodRequest->request_id) }}">#{{ str_pad($bloodRequest->request_id, 1, '0', STR_PAD_LEFT) }}</a>
        <span class="breadcrumb-sep">›</span>
        <span>Edit</span>
      </div>
      <h1 class="page-title">Edit Blood Request</h1>
      <p class="page-subtitle">Only pending requests can be modified.</p>
    </div>
    <a href="{{ route('hospital.requests.show', $bloodRequest->request_id) }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  @if($errors->any())
    <div style="background:rgba(192,57,43,0.06); border:1px solid rgba(192,57,43,0.2); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem;">
      <ul style="margin:0; padding-left:1.25rem; font-size:0.875rem; color:var(--primary); line-height:1.8;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  @php
    $existingAddress = null;
    if ($bloodRequest->remarks && str_contains($bloodRequest->remarks, "\nDelivery address: ")) {
        [$remarksPart, $addressPart] = explode("\nDelivery address: ", $bloodRequest->remarks, 2);
    } else {
        $remarksPart = $bloodRequest->remarks;
        $addressPart = null;
    }
  @endphp

  <form method="POST" action="{{ route('hospital.requests.update', $bloodRequest->request_id) }}"
    x-data="{
      qty: {{ old('quantity', $bloodRequest->quantity) }},
      fulfillment: '{{ old('fulfillment', $bloodRequest->fulfillment_type) }}'
    }">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:1.5rem; align-items:start;">

      {{-- ── LEFT COLUMN ─────────────────────────────────── --}}
      <div>

        {{-- Section 1: Hospital Info --}}
        <div class="dash-card" style="margin-bottom:1.25rem;">
          <div class="dash-card-header">
            <div style="display:flex; align-items:center; gap:0.6rem;">
              <div style="width:22px; height:22px; border-radius:50%; background:#C0392B; color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">1</div>
              <h3 class="dash-card-title">Hospital Information</h3>
            </div>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
              <div>
                <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Hospital Name</div>
                <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $hospital->hospital_name }}</div>
              </div>
              <div>
                <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">License No.</div>
                <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $hospital->license_number }}</div>
              </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
              <div>
                <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Contact Number</div>
                <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $hospital->contact_number ?? '—' }}</div>
              </div>
              <div>
                <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Email</div>
                <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ auth()->user()->email }}</div>
              </div>
            </div>
            <div>
              <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Address</div>
              <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $hospital->address ?? '—' }}</div>
            </div>
          </div>
        </div>

        {{-- Section 2: Request Details --}}
        <div class="dash-card">
          <div class="dash-card-header">
            <div style="display:flex; align-items:center; gap:0.6rem;">
              <div style="width:22px; height:22px; border-radius:50%; background:#C0392B; color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">2</div>
              <h3 class="dash-card-title">Request Details</h3>
            </div>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">

            {{-- Blood Type + Units --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Blood Type <span style="color:#E74C3C;">*</span></label>
                <select name="blood_type_id" required class="form-input form-input-light">
                  <option value="">Select blood type</option>
                  @foreach($bloodTypes as $bt)
                    <option value="{{ $bt->blood_type_id }}"
                      {{ old('blood_type_id', $bloodRequest->blood_type_id) == $bt->blood_type_id ? 'selected' : '' }}>
                      {{ $bt->type_name }}
                    </option>
                  @endforeach
                </select>
                @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Units Required <span style="color:#E74C3C;">*</span></label>
                <div style="display:flex; align-items:center;">
                  <button type="button" @click="if(qty > 1) qty--"
                    style="width:42px; height:42px; border-radius:8px 0 0 8px; border:1.5px solid var(--border-light); border-right:none; background:#f8f9fc; color:#555; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(192,57,43,0.06)'; this.style.color='var(--primary)'"
                    onmouseout="this.style.background='#f8f9fc'; this.style.color='#555'">−</button>
                  <input type="number" name="quantity" x-model.number="qty"
                    min="1" max="100" required
                    style="flex:1; height:42px; border:1.5px solid var(--border-light); border-left:none; border-right:none; text-align:center; font-size:1rem; font-weight:700; color:#1a1a2e; background:#fff; outline:none; min-width:0; -moz-appearance:textfield;"/>
                  <button type="button" @click="if(qty < 100) qty++"
                    style="width:42px; height:42px; border-radius:0 8px 8px 0; border:1.5px solid var(--border-light); border-left:none; background:#f8f9fc; color:#555; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(192,57,43,0.06)'; this.style.color='var(--primary)'"
                    onmouseout="this.style.background='#f8f9fc'; this.style.color='#555'">+</button>
                </div>
                <div style="font-size:0.72rem; color:#bbb; margin-top:0.3rem;">Max 100 units per request</div>
                @error('quantity')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Urgency + Fulfillment --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Urgency Level <span style="color:#E74C3C;">*</span></label>
                <select name="urgency" required class="form-input form-input-light">
                  <option value="routine"   {{ old('urgency', $bloodRequest->urgency) === 'routine'   ? 'selected' : '' }}>Routine — 24–48 hrs</option>
                  <option value="urgent"    {{ old('urgency', $bloodRequest->urgency) === 'urgent'    ? 'selected' : '' }}>Urgent — 12–24 hrs</option>
                  <option value="emergency" {{ old('urgency', $bloodRequest->urgency) === 'emergency' ? 'selected' : '' }}>Emergency — Immediately</option>
                </select>
                @error('urgency')<div class="form-error">{{ $message }}</div>@enderror
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Fulfillment Method <span style="color:#E74C3C;">*</span></label>
                <select name="fulfillment" x-model="fulfillment" required class="form-input form-input-light">
                  <option value="pickup"   {{ old('fulfillment', $bloodRequest->fulfillment_type) === 'pickup'   ? 'selected' : '' }}>Pickup at blood bank</option>
                  <option value="delivery" {{ old('fulfillment', $bloodRequest->fulfillment_type) === 'delivery' ? 'selected' : '' }}>Delivery to hospital</option>
                </select>
                @error('fulfillment')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="form-group" style="margin-bottom:1.25rem;">
    <label class="form-label">Needed By <span style="font-weight:400; color:#bbb;">(optional)</span></label>
    <input type="date" name="needed_by"
        value="{{ old('needed_by', $bloodRequest->needed_by?->format('Y-m-d')) }}"
        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
        class="form-input form-input-light"/>
    <div style="font-size:0.72rem; color:#bbb; margin-top:0.3rem;">
        Specify when you need the blood units delivered or ready for pickup.
    </div>
    @error('needed_by')<div class="form-error">{{ $message }}</div>@enderror
</div>

            {{-- Delivery address (conditional) --}}
            <div class="form-group" style="margin-bottom:1.25rem;" x-show="fulfillment === 'delivery'" x-cloak>
              <label class="form-label">Delivery Address <span style="color:#E74C3C;">*</span></label>
              <textarea name="delivery_address" rows="2"
                class="form-input form-input-light" style="resize:none;"
                placeholder="Full delivery address for blood units..."
                :required="fulfillment === 'delivery'">{{ old('delivery_address', $addressPart) }}</textarea>
              @error('delivery_address')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Remarks --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
              <label class="form-label">Additional Notes <span style="font-weight:400; color:#bbb;">(optional)</span></label>
              <textarea name="remarks" rows="3"
                class="form-input form-input-light" style="resize:none;"
                placeholder="e.g. For surgery scheduled tomorrow. Patient is adult male.">{{ old('remarks', $remarksPart) }}</textarea>
              @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Info banner --}}
            <div style="background:rgba(41,128,185,0.06); border:1px solid rgba(41,128,185,0.18); border-radius:10px; padding:0.75rem 1rem; display:flex; gap:0.6rem; align-items:flex-start; margin-bottom:1.25rem;">
              <svg width="14" height="14" fill="none" stroke="#2980B9" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <p style="font-size:0.78rem; color:#1a5276; line-height:1.6; margin:0;">
                Changes apply only while the request is <strong>pending</strong>. Once approved, the request is locked.
              </p>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; gap:0.75rem;">
              <a href="{{ route('hospital.requests.show', $bloodRequest->request_id) }}"
                style="flex:1; text-align:center; padding:0.7rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                Discard
              </a>
              <button type="submit" class="btn btn-dash-primary" style="flex:2; justify-content:center; border-radius:10px; padding:0.7rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
              </button>
            </div>

          </div>
        </div>

      </div>

      {{-- ── RIGHT COLUMN ─────────────────────────────────── --}}
      <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Current Availability --}}
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Current Availability</h3>
            <span style="font-size:0.72rem; color:#bbb;">Available units only</span>
          </div>
          <div class="dash-card-body" style="padding:1rem 1.25rem;">
            <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:0.5rem; margin-bottom:0.85rem;">
              @foreach($inventory as $type => $data)
                @php
                  $btColors = ['A+'=>'#3498DB','A-'=>'#2980B9','B+'=>'#9B59B6','B-'=>'#8E44AD','AB+'=>'#1ABC9C','AB-'=>'#16A085','O+'=>'#E74C3C','O-'=>'#C0392B'];
                  $count = $data['count'];
                  $color = $btColors[$type] ?? '#888';
                @endphp
                <div style="text-align:center; padding:0.65rem 0.25rem; border-radius:9px; background:{{ $color }}0d; border:1.5px solid {{ $count === 0 ? '#E74C3C44' : $color . '33' }};">
                  <div style="font-family:var(--font-mono); font-size:0.85rem; font-weight:800; color:{{ $color }}; line-height:1; margin-bottom:0.2rem;">{{ $type }}</div>
                  <div style="font-size:1.1rem; font-weight:800; color:{{ $count === 0 ? '#E74C3C' : '#1a1a2e' }}; line-height:1;">{{ $count }}</div>
                </div>
              @endforeach
            </div>
            <div style="font-size:0.75rem; color:#888; line-height:1.5;">
              Check availability before submitting. Low or 0 units may delay fulfillment.
            </div>
          </div>
        </div>

        {{-- How It Works --}}
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">How It Works</h3>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
            @foreach([
              ['Submit',  'Submit a request with blood type, quantity and urgency.',         '#C0392B'],
              ['Review',  'Staff reviews and checks inventory availability.',                '#2980B9'],
              ['Approve', 'You are notified of approval or rejection.',                     '#27AE60'],
              ['Fulfill', 'Blood units reserved for pickup or delivery.',                   '#9B59B6'],
            ] as $i => [$title, $desc, $color])
              <div style="display:flex; gap:1rem; padding-bottom:{{ $i < 3 ? '1.1rem' : '0' }}; position:relative;">
                @if($i < 3)
                  <div style="position:absolute; left:13px; top:26px; bottom:0; width:2px; background:{{ $color }}20; z-index:0;"></div>
                @endif
                <div style="width:26px; height:26px; border-radius:50%; background:{{ $color }}15; border:2px solid {{ $color }}50; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; z-index:1; font-size:0.7rem; font-weight:800; color:{{ $color }}; font-family:var(--font-mono);">
                  {{ $i + 1 }}
                </div>
                <div style="padding-top:3px;">
                  <div style="font-size:0.84rem; font-weight:700; color:#1a1a2e; margin-bottom:0.2rem;">{{ $title }}</div>
                  <div style="font-size:0.77rem; color:#888; line-height:1.5;">{{ $desc }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Emergency Contact --}}
        <div style="background:rgba(192,57,43,0.04); border:1px solid rgba(192,57,43,0.12); border-radius:14px; padding:1.25rem;">
          <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#C0392B; margin-bottom:0.65rem;">Emergency Contact</div>
          <div style="font-size:0.85rem; color:#555; line-height:1.8;">
            For urgent requests, call us directly:<br>
            <strong style="color:#1a1a2e; font-size:1rem;">+63 (82) 123-4567</strong><br>
            <span style="font-size:0.78rem; color:#888;">Available 24 hours for emergencies</span>
          </div>
          <div style="margin-top:0.85rem; font-size:0.78rem; color:#888; display:flex; align-items:center; gap:0.4rem;">
            <svg width="12" height="12" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            apolife.org.ph
          </div>
        </div>

      </div>

    </div>
  </form>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.2fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 480px) {
      div[style*="grid-template-columns:repeat(4,1fr)"] { grid-template-columns: repeat(2,1fr) !important; }
    }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>

</x-app-layout>