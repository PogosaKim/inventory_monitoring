<table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
    <!-- Header -->
    <tr>
        <td bgcolor="#f8f9fa" style="padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; color: #333333;">Inventory Request Approved by Finance</h1>
            <p style="margin: 5px 0; font-size: 14px; color: #666666;">Request Code: {{ $requestCode }}</p>
            <p style="margin: 5px 0; font-size: 14px; color: #666666;">Date: {{ date('Y-m-d') }}</p>
        </td>
    </tr>

    <!-- Main Content -->
    <tr>
        <td bgcolor="#ffffff" style="padding: 20px;">
            <h3 style="font-size: 18px; color: #333333; margin: 0 0 15px 0; font-weight: bold;">Approved Inventory Details</h3>
            <table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse;">
                <thead>
                    <tr bgcolor="#f1f3f5">
                        <th style="padding: 10px; font-size: 14px; color: #333333; text-align: left;">Item Name</th>
                        <th style="padding: 10px; font-size: 14px; color: #333333; text-align: left;">Quantity</th>
                        <th style="padding: 10px; font-size: 14px; color: #333333; text-align: left;">Unit Price</th>
                        <th style="padding: 10px; font-size: 14px; color: #333333; text-align: left;">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventoryDetails as $item)
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #666666;">{{ $item['name'] }}</td>
                        <td style="padding: 10px; font-size: 14px; color: #666666;">{{ $item['quantity'] }}</td>
                        <td style="padding: 10px; font-size: 14px; color: #666666;">{{ number_format($item['unit_price'], 2) }}</td>
                        <td style="padding: 10px; font-size: 14px; color: #666666;">{{ number_format($item['total_price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="margin: 15px 0 5px 0; font-size: 14px; color: #666666;">Thank you,</p>
            <p style="margin: 0; font-size: 14px; color: #666666; font-weight: bold;">CUSTODIAN OFFICE TRACK & REQUEST</p>
        </td>
    </tr>
</table>