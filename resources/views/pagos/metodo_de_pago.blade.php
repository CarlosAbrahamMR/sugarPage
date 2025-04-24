@extends('layouts.appsinvue')
@section('styles')
    <style>
        .credit-card-div span {
            padding-top: 10px;
        }

        .credit-card-div img {
            padding-top: 30px;
        }

        .credit-card-div .small-font {
            font-size: 9px;
        }

        .credit-card-div .pad-adjust {
            padding-top: 10px;
        }
    </style>
@endsection
@section('content')

    <div class="page-section">
        <div class="row">
            <div class="col-md-12 col-lg-8 col-md-offset-1 col-lg-offset-2">
                @if (\Session::has('succes'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div> {{ session()->get('succes') }}</div>
                    </div>
                @endif
                
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div> {{ session()->get('error') }}</div>
                    </div>
                @endif
                <h4 class="page-section-heading">Actualiza tu método de pago</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="credit-card-div">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <div class="form-control" id="card-element"></div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 pad-adjust">
                                            <select class="form-control" id="country" name="country">
                                                <option value="1">Eu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 pad-adjust">
                                            <input placeholder="Titular" type="email" id="card-holder-name"
                                                   name="card-holder-name"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
                                            <button id="card-button" onclick="clic()"
                                                    data-secret="{{ $intent->client_secret }}"
                                                    class="btn btn-success"
                                            >
                                                Actualizar método de pago
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="py-12">
                            <form id="payment_method_form" method="post"
                                  action="{{ route("agreagar.tarjeta") }}">
                                @csrf
                                <input type="hidden" id="card_holder_name" name="card_holder_name"/>
                                <input type="hidden" id="pm" name="pm"/>
                                <input type="hidden" id="country_id" name="country_id"/>
                                <input type="hidden" id="stripe" name="stripe"/>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config("cashier.key") }}');

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const country = document.getElementById('country');
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;

        async function clic() {
            console.log('entra')
            const {setupIntent, error} = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {name: cardHolderName.value}
                    }
                }
            );

            if (error) {
                alert(error.message)
            } else {
                await stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        alert(result.error.message)
                        console.log(result.error.message)
                    } else {
                        // Send the token to your server.
                        var token= result.token;
                        document.getElementById("pm").value = setupIntent.payment_method;
                        document.getElementById("card_holder_name").value = cardHolderName.value;
                        document.getElementById("country_id").value = country.value;
                        document.getElementById("stripe").value = token.id;
                        document.getElementById("payment_method_form").submit();
                    }
                });
                
            }
        }

        cardButton.addEventListener('click', async (e) => {
            console.log('entra')
            const {setupIntent, error} = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {name: cardHolderName.value}
                    }
                }
            );

            if (error) {
                alert(error.message)
            } else {
                await stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        alert(result.error.message)
                        console.log(result.error.message)
                    } else {
                        // Send the token to your server.
                        var token= result.token;
                        document.getElementById("pm").value = setupIntent.payment_method;
                        document.getElementById("card_holder_name").value = cardHolderName.value;
                        document.getElementById("country_id").value = country.value;
                        document.getElementById("stripe").value = token.id;
                        document.getElementById("payment_method_form").submit();
                    }
                });
            }
        });
    </script>
@endsection
