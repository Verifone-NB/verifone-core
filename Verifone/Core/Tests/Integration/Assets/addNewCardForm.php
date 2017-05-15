<?php
/**
 * NOTICE OF LICENSE 
 *
 * This source file is released under commercial license by Lamia Oy. 
 *
 * @copyright  Copyright (c) 2017 Lamia Oy (https://lamia.fi) 
 * @author     Irina Mäkipaja <irina@lamia.fi>
 */

return '<form method="POST" id="verifone_form" action="https://epayment.test.point.fi/pw/payment" target="_parent">
Redirecting to VerifonePayment.
<input type="hidden" name="i-f-1-11_interface-version" value="5" />
<input type="hidden" name="s-f-1-36_merchant-agreement-code" value="demo-merchant-agreement" />
<input type="hidden" name="s-f-1-30_software" value="Magento" />
<input type="hidden" name="s-f-1-10_software-version" value="1.9.2.2" />
<input type="hidden" name="t-f-14-19_payment-timestamp" value="' . gmdate('Y-m-d H:i:s') . '" />
<input type="hidden" name="i-t-1-1_skip-confirmation-page" value="1" />
<input type="hidden" name="s-f-5-256_cancel-url" value="http://www.testikauppa.fi/cancel" />
<input type="hidden" name="s-f-5-256_error-url" value="http://www.testikauppa.fi/error" />
<input type="hidden" name="s-f-5-256_expired-url" value="http://www.testikauppa.fi/expired" />
<input type="hidden" name="s-f-5-256_rejected-url" value="http://www.testikauppa.fi/rejected" />
<input type="hidden" name="s-f-5-256_success-url" value="http://www.testikauppa.fi/success" />
<input type="hidden" name="i-t-1-1_save-payment-method" value="2" />
<input type="hidden" name="t-f-14-19_order-timestamp" value="' . gmdate('Y-m-d H:i:s') . '" />
<input type="hidden" name="s-f-1-36_order-number" value="addNewCard" />
<input type="hidden" name="l-f-1-20_order-gross-amount" value="1" />
<input type="hidden" name="l-f-1-20_order-net-amount" value="1" />
<input type="hidden" name="l-f-1-20_order-vat-amount" value="0" />
<input type="hidden" name="s-f-32-32_payment-token" value="placeholder_for_payment_token" />
<input type="hidden" name="s-t-1-30_bi-name-0" value="fake product" />
<input type="hidden" name="i-t-1-11_bi-unit-count-0" value="1" />
<input type="hidden" name="i-t-1-4_bi-discount-percentage-0" value="0" />
<input type="hidden" name="i-t-1-4_bi-vat-percentage-0" value="0" />
<input type="hidden" name="l-t-1-20_bi-unit-cost-0" value="1" />
<input type="hidden" name="l-t-1-20_bi-net-amount-0" value="1" />
<input type="hidden" name="l-t-1-20_bi-gross-amount-0" value="1" />
<input type="hidden" name="s-f-1-30_buyer-first-name" value="Example" />
<input type="hidden" name="s-f-1-30_buyer-last-name" value="Exemplar" />
<input type="hidden" name="s-t-1-30_buyer-phone-number" value="0401234567" />
<input type="hidden" name="s-f-1-100_buyer-email-address" value="example@domain.fi" />
<input type="hidden" name="locale-f-2-5_payment-locale" value="fi_FI" />
<input type="hidden" name="s-t-1-36_order-note" value="" />
<input type="hidden" name="i-f-1-3_order-currency-code" value="978" />
<input type="hidden" name="s-t-256-256_signature-two" value="placeholder_sig_one" />
<br>
<script type="text/javascript">document.getElementById("verifone_form").submit();</script>
</form>';
