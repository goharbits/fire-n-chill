            <div class="field-group">
                <h3 class="field-group--label text-center">User Detail</h3>
                <!-- First Name -->
                <div class="card-input">
                    <label for="FirstName" class="card-input__label">First name</label>
                    <input type="text" class="card-input__input" id="FirstName" name="first_name"
                        value="{{ old('first_name') }}" required>
                </div>
                <!-- Last Name -->
                <div class="card-input">
                    <label for="LastName" class="card-input__label">Last name</label>
                    <input type="text" class="card-input__input" id="LastName" name="last_name"
                        value="{{ old('last_name') }}" required>
                </div>
                <!-- Email -->
                <div class="card-input">
                    <label for="email" class="card-input__label">Email</label>
                    <input type="email" class="card-input__input" id="email" name="email"
                        value="{{ old('email') }}" required>
                </div>
            </div>
