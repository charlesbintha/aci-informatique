<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Nouvelle demande de devis — ACI Informatique</title></head>
<body style="margin:0;padding:0;background-color:#f3f1ea;color:#20241f;font-family:Arial,Helvetica,sans-serif;">
<div style="display:none;max-height:0;overflow:hidden;">Une nouvelle demande pour {{ $serviceName }} est arrivée sur le site ACI Informatique.</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f1ea;"><tr><td align="center" style="padding:24px 12px;">
<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="width:100%;max-width:600px;background-color:#ffffff;">
<tr><td style="padding:26px 28px;background-color:#20241f;color:#ffffff;"><span style="font-size:27px;font-weight:bold;letter-spacing:-1px;color:#c4ee18;">ACI</span> <span style="font-size:20px;color:#ffffff;">Informatique</span></td></tr>
<tr><td style="padding:30px 28px;background-color:#c4ee18;color:#171817;"><p style="margin:0 0 12px;font-size:11px;letter-spacing:2px;">DEMANDE N° {{ $requestId }}</p><h1 style="margin:0;font-size:30px;font-weight:600;line-height:1.2;">Un nouveau projet<br>à accompagner.</h1></td></tr>
<tr><td style="padding:28px;"><p style="margin:0 0 22px;font-size:15px;line-height:1.6;">Bonjour Oumar,<br>Une demande de devis a été déposée sur votre site.</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:14px;line-height:1.6;table-layout:fixed;">
@foreach(['Prestation' => $serviceName, 'Nom' => $quote['name'], 'Organisation' => $quote['organization'] ?? 'Non renseignée', 'E-mail' => $quote['email'], 'Téléphone' => $quote['phone'] ?? 'Non renseigné'] as $label => $value)
<tr><td width="110" valign="top" style="padding:11px 10px 11px 0;border-bottom:1px solid #e7e8e1;color:#64665e;">{{ $label }}</td><td valign="top" style="padding:11px 0;border-bottom:1px solid #e7e8e1;word-break:break-word;overflow-wrap:anywhere;">{{ $value }}</td></tr>
@endforeach
</table>
<h2 style="margin:28px 0 12px;font-size:17px;">Le projet</h2><div style="font-size:15px;line-height:1.75;word-break:break-word;overflow-wrap:anywhere;">{!! nl2br(e($quote['message'])) !!}</div>
<table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:28px;"><tr><td bgcolor="#20241f" style="padding:15px 22px;"><a href="mailto:{{ $quote['email'] }}" style="color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">Répondre au client ↗</a></td></tr></table>
<p style="margin:18px 0 0;font-size:12px;line-height:1.7;color:#64665e;">Vous pouvez aussi utiliser « Répondre » dans votre messagerie. La demande est enregistrée sous le numéro {{ $requestId }}.</p>
</td></tr>
<tr><td style="padding:20px 28px;border-top:1px solid #e7e8e1;font-size:12px;line-height:1.7;color:#64665e;">ACI Informatique · Dakar, Sénégal<br><a href="https://aci-informatique.com" style="color:#526800;">aci-informatique.com</a></td></tr>
</table></td></tr></table>
</body></html>
