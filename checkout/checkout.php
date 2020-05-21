<!--checkout overlay-->
<div class="checkout-overlay">
    <!-- Cart -->
    <div class="checkout">
        <div class="headers">
            <!--checkout close button-->
            <span class="close-checkout"><i class="fa fa-window-close"></i></span>
            <!--checkout header-->
            <div class="checkout-header">
                <ul>
                    <li class="delivery-header active active-now">DELIVERY</li>
                    <li class="payment-header disabled">PAYMENT</li>
                    <li class="statement-header disabled">STATEMENT</li>
                </ul>
            </div>
        </div>
        <!-- checkout content -->
        <form action="" name="checkout-form">
            <div class="checkout-content">
                <div class="delivery">

                    <div class="delivery-method">
                        <table>
                            <tr>
                                <caption>DELIVERY METHOD</caption>
                            </tr>
                            <tr>
                                <td><input type="radio" name="delivery-option" id="delivery-option1"
                                        value="deliver-home">Deliver to Home or Office</td>
                            </tr>
                            <tr>
                                <td><input type="radio" name="delivery-option" id="delivery-option2"
                                        value="pick-up">Pick-up from store</td>
                            </tr>


                        </table>
                    </div>
                    <div class="deliver-home">
                        <div class="customer-addresses">
                            <div class="saved-addresses">

                            </div>
                            <p><i class="fa fa-plus"></i></p>
                        </div>

                        <table class="home-address-table">
                            <tr>
                                <caption>Add Address</caption>
                            </tr>

                            <tr>

                                <td><input type="text" name="cphone" id="cphone" placeholder="CONTACT PHONE"></td>
                            </tr>
                            <tr>

                                <td><input type="text" name="city" id="city" placeholder="CITY"></td>
                            </tr>
                            <tr>

                                <td><input type="text" name="street" id="street" placeholder="STREET"></td>
                            </tr>
                            <tr>

                                <td><input type="text" name="house" id="house" placeholder="HOUSE"></td>
                            </tr>
                            <tr>
                                <td><input type="button" value="Save Address" class="submit-address"></td>
                            </tr>

                        </table>

                        <div>
                            <p>Goods are shopped and shipped as soon as your order is recieved.</p>
                            <p>Shopping fee applys</p>
                            <p>
                                An extra delivery charge for goods not collected in store and another extra delivery
                                charge for shipping in locations out of the location in which the store belongs.
                            </p>
                        </div>
                    </div>
                    <div class="pick-from-store">
                        <p>Goods are shopped as soon as your order is recieved.</p>
                        <p>Shopping fee applys</p>
                    </div>


                </div>
                <div class="payment">
                    <h4>SELECT PAYMENT METHOD</h4>

                    <div class="payment-method-div">
                        <p><input type="radio" name="payment-method" id="payment-method1" value="mpesa-on-delivery">
                            <b>Mpesa On Delivery</b></p>
                        <div class="m-delivery-payment-details">
                            <p>Pay via Mpesa when our delivery Associates bring it to your home or when you pick
                                the
                                delivery packages at the store</p>
                        </div>
                    </div>
                    <div class="payment-method-div">
                        <p><input type="radio" name="payment-method" id="payment-method2" value="cash-on-delivery"><b>
                                Cash On Delivery</b></p>
                        <div class="cash-payment-details">
                            <p>Pay cash when our delivery Associates bring it to your home or when you pick
                                the
                                delivery packages at the store</p>
                            <p>PLEASE carry the exact amount for easy and fast payment </p>
                        </div>
                    </div>

                </div>
                <div class="statement">
                    <div class="statement-details address-details">
                        <h5>YOUR ADDRESS</h5>
                        <div class="address-chosen">
                        </div>
                    </div>
                    <div class="statement-details">
                        <h5>DELIVERY METHOD</h5>
                        <div class="delivery-details">
                        </div>
                    </div>
                    <div class="statement-details">
                        <h5>PAYMENT METHOD</h5>
                        <div class="payment-details">
                        </div>
                    </div>
                </div>

                <!--totals-->
                <div class="checkout-totals">
                    <table>
                        <tr>
                            <caption>
                                <h3>TOTALS</h3>
                            </caption>
                        </tr>
                        <tr>
                            <td>Total: Ksh. </td>
                            <td>
                                <h4 class="checkout-total">6767</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>Shopping Fee: Ksh. </td>
                            <td>
                                <h4 class="shopping-fee">56767</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>Shipping Fee: Ksh. </td>
                            <td>
                                <h4 class="shipping-fee"></h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="grand-total-td">Grand Total: Ksh. </td>
                            <td class="grand-total-td"> <b><span class="grand-total"> 0</span></b></td>
                        </tr>
                        <p></p>
                        <tr>
                            <td class="checkout-error"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Proceed to payment" class="proceed-btn proceed-payment">
                            </td>
                        </tr>
                    </table>
                </div>
                <!--end of totals-->

            </div>
            <!-- End of checkout content -->
            <!-- checkout footer -->
    </div>
    </form>
    <!-- End of checkout -->
</div>
<!-- End of checkout overlay -->