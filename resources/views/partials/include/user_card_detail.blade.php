 <div class="field-group">
     <h3 class="field-group--label text-center">Card details</h3>

     <div class="card-input">
         <label for="CreditCardNumber" class="card-input__label">Card
             Number</label>
         <input type="tel" name="CreditCardNumber" data-encrypted-field class="card-input__input"
             value="{{ old('CreditCardNumber') }}" required autocomplete="off" maxlength="19" />
     </div>
     <div class="card-input">
         <label for="CardHolder" class="card-input__label">Card Holder</label>
         <input id="CardHolder" type="text" name="CardHolder" value="{{ old('CardHolder') }}" required
             class="card-input__input" autocomplete="off" />
     </div>
     <div class="card-form__row">
         <div class="card-form__col">
             <div class="card-form__group">
                 <label for="ExpMonth" class="card-input__label">Expiration
                     Date</label>
                 <select id="ExpMonth" name="ExpMonth" data-encrypted-field class="card-input__input -select"
                     value="{{ old('ExpMonth') }}" required></select>
                 <select id="ExpYear" name="ExpYear" data-encrypted-field class="card-input__input -select"
                     value="{{ old('ExpYear') }}" required></select>
             </div>
         </div>
         <div class="card-form__col -cvv">
             <div class="card-input">
                 <label for="CVV" class="card-input__label">CVV</label>
                 <input type="tel" data-encrypted-field name="CVV" class="card-input__input" maxlength="4"
                     autocomplete="off" required />
             </div>
         </div>
     </div>
 </div>
