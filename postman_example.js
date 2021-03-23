var axios = require('axios');
var data = JSON.stringify({
    "tour_id":58,
    "603f9da24cc5c":1,
    "first_name":"",
    "last_name":"",
    "email":"jmadrazo7@gmail.com",
    "age":"23",
    "nit_name":23,
    "nit":23,
    "receipt_address":"190.104.120.207",
    "total_discount":0,
    "total_discounted":0,
    "total_amount":300,
    "payment_type":"epay",
    "credit_card_cvv":123,
    "credit_card_expiry":2401,
    "credit_card_name":"noreal name",
    "credit_card_number":"4000 0000 0000 0416",
    "utm_medium":"whatsapp",
    "utm_source":"link"}
);

var config = {
  method: 'post',
  url: 'https://eventos.avantlife.gt/api/v1/tours/58/book_and_pay/web',
  headers: { 
    'Content-Type': 'application/json', 
    'Cookie': '__cfduid=d0a2fba86c6577bcc986177054bdf81c81616178191'
  },
  data : data
};

axios(config)
.then(function (response) {
  console.log(JSON.stringify(response.data));
})
.catch(function (error) {
  console.log(error);
});
