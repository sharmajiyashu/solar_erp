<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proforma Invoice - {{ $lead->lead_no }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #333; line-height: 1.4; margin: 0; padding: 0; background-color: #fcfcfc; }
        
        /* Non-printable actions */
        .no-print-header { 
            background: #f8f9fa; 
            padding: 15px 50px; 
            border-bottom: 1px solid #ddd; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        @media print { .no-print { display: none !important; } }
        
        .btn-whatsapp { 
            background-color: #25D366; 
            color: white; 
            padding: 8px 16px; 
            border-radius: 5px; 
            text-decoration: none; 
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            margin-right: 10px;
        }

        .invoice-container { background: #fff; min-height: 100%; position: relative; box-shadow: 0 0 20px rgba(0,0,0,0.1); max-width: 900px; margin: 20px auto; }
        @media print { 
            .invoice-container { margin: 0; box-shadow: none; max-width: 100%; } 
        }
        
        /* Header Section */
        .header-stripe { background: #28a745; height: 10px; width: 100%; }
        .header-content { padding: 40px 50px 20px; }
        .logo-box { float: left; width: 50%; }
        .logo-img { height: 80px; width: auto; }
        .invoice-info { float: right; width: 40%; text-align: right; }
        .invoice-info h1 { margin: 0; font-size: 32px; color: #1e7e34; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-info p { margin: 5px 0; color: #666; font-size: 12px; }
        
        .clearfix { clear: both; }

        /* Company & Client Details */
        .details-section { padding: 20px 50px; background: #fff; }
        .col-box { float: left; width: 48%; }
        .col-box-right { float: right; width: 48%; }
        .section-label { color: #28a745; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; font-size: 11px; }
        .address-box { font-size: 12px; color: #444; }
        .bold { font-weight: bold; color: #000; }

        /* Table Styles */
        .items-section { padding: 0 50px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 8px; overflow: hidden; }
        .items-table th { background: #28a745; color: #fff; text-align: left; padding: 12px; font-size: 11px; text-transform: uppercase; }
        .items-table td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .items-table tr:nth-child(even) { background: #f9f9f9; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Summary Section */
        .summary-section { padding: 30px 50px; }
        .notes-box { float: left; width: 55%; font-size: 11px; color: #777; }
        .notes-box h4 { color: #333; margin-bottom: 5px; }
        .totals-box { float: right; width: 35%; }
        .totals-table { width: 100%; }
        .totals-table td { padding: 8px 0; font-size: 13px; }
        .grand-total-row { border-top: 2px solid #28a745; font-size: 18px; font-weight: bold; color: #1e7e34; }

        /* Footer */
        .footer { padding: 20px 50px; border-top: 1px solid #eee; margin-top: 50px; text-align: center; font-size: 10px; color: #999; }
        .signature-section { margin-top: 40px; text-align: right; padding-right: 50px; }
        .signature-line { display: inline-block; width: 180px; border-top: 1px solid #333; margin-top: 60px; text-align: center; font-weight: bold; padding-top: 5px; }

        /* Watermark style (Solar icon would be nice, but keeping it simple for dompdf) */
        .watermark { position: absolute; top: 30%; left: 20%; font-size: 100px; color: rgba(40, 167, 69, 0.05); transform: rotate(-45deg); z-index: -1; }
    </style>
</head>
<body>
    <div class="no-print no-print-header">
        <div class="fw-bold text-dark">Proforma Invoice Preview</div>
        <div class="d-flex">
            <a href="{{ route('admin.leads.proforma.generate', $lead->id) }}" class="btn-download">
                Download PDF
            </a>
        </div>
    </div>

    <div class="invoice-container">
        <div class="header-stripe"></div>
        
        <div class="header-content">
            <div class="logo-box">
                @php
                    $logoPath = public_path('logo.jpg');
                    if (file_exists($logoPath)) {
                        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
                    } else {
                        $logoBase64 = '';
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img">
                @else
                    <h2 style="color: #28a745; margin: 0;">ARKSHAKTI</h2>
                    <p style="font-size: 10px; margin: 0;">POWER SOLUTIONS PVT LTD</p>
                @endif
            </div>
            <div class="invoice-info">
                <h1>PROFORMA</h1>
                <p><span class="bold">INV # :</span> {{ $lead->lead_id_custom ?? $lead->lead_no }}</p>
                <p><span class="bold">DATE :</span> {{ date('F d, Y') }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="details-section">
            <div class="col-box">
                <div class="section-label">Our Details</div>
                <div class="address-box">
                    <span class="bold">Arkshakti Power Solutions Pvt Ltd</span><br>
                    Office No. 12, Energy Plaza, Sector 45<br>
                    Noida, UP - 201301<br>
                    <span class="bold">GSTIN:</span> 09ABCDE1234F1Z5<br>
                    <span class="bold">Email:</span> billing@arkshakti.com
                </div>
            </div>
            <div class="col-box-right">
                <div class="section-label">Client Details</div>
                <div class="address-box">
                    <span class="bold">{{ $lead->customer->name }}</span><br>
                    {{ $lead->customer->address ?? 'N/A' }}<br>
                    <span class="bold">Phone:</span> +91 {{ $lead->customer->mobile }}<br>
                    <span class="bold">Lead No:</span> {{ $lead->lead_no }}
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="40%">Item Description</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Rate</th>
                        <th class="text-center">GST %</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->procurementItems as $item)
                    <tr>
                        <td>
                            <span class="bold">{{ $item->product->subtype }}</span><br>
                            <small style="color: #555;">{{ $item->product->company }}</small>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->gst_percentage }}%</td>
                        <td class="text-right bold">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <div class="notes-box">
                <h4>Terms & Conditions</h4>
                <ol>
                    <li>This is a Proforma Invoice, not a Tax Invoice.</li>
                    <li>Payment should be made in favor of "Arkshakti Power Solutions Pvt Ltd".</li>
                    <li>Validity of this quote is 7 days from the date of issue.</li>
                    <li>Goods once sold will not be taken back.</li>
                </ol>
            </div>
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">₹{{ number_format($lead->procurementItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total GST</td>
                        <td class="text-right">₹{{ number_format($lead->procurementItems->sum('tax_amount'), 2) }}</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td>TOTAL</td>
                        <td class="text-right">₹{{ number_format($lead->procurementItems->sum('total'), 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="signature-section">
            <div class="signature-line">Authorized Signatory</div>
        </div>

        <div class="footer">
            <p>Arkshakti Power Solutions Pvt Ltd | Website: www.arkshakti.com | Thank you for choosing Solar Energy!</p>
        </div>
        
        <div class="watermark">ARKSHAKTI</div>
    </div>
</body>
</html>

