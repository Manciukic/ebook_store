# TODO 
 - [ ] Authentication
    - [x] Remove username (email only registration) `R`
    - [x] Confirm newly registered account (active user field in users table
         and new registration link table is needed). Send email alert to
         existing users if their email is inserted `R`
    - [x] If users tries to log but has not confirmed email, resend confirmation
         email. `R`
    - [ ] ~~ Show "resend confirmation email" button `R` ~~
    - [ ] Account locking on 5 consecutive failed attempts `Z`
    - [ ] Hash password with BCRYPT (see password_hash PHP function) `Z`
    - [ ] Log-out `Z`
    - [ ] Add reCAPTCHA v2 `Z`
    - [x] Fix hard-coded security question `M`
    - [-]  Add server-side controls to login and registration form `M`
    - [x] Change password `M`
    - [ ] Recover password `M`
    - [ ] Unique "standard" login page in checkout
 - [ ] Order
    - [ ] Client-side validation of credit card attributes `R`
    - [x] Check duplicate credit card number
 - [ ] Other
    - [x] User profile page
    - [x] Owned e-books page
    - [x] Orders page
