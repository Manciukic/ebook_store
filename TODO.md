# TODO 
## Authentication
- [x] Remove username (email only registration) `R`
- [x] Confirm newly registered account (active user field in users table
   and new registration link table is needed). Send email alert to
   existing users if their email is inserted `R`
- [x] If users tries to log but has not confirmed email, resend confirmation
   email. `R`
- [ ] ~~ Show "resend confirmation email" button `R` ~~
- [x] Account locking on 5 consecutive failed attempts `Z`
- [x] Hash password with BCRYPT (see password_hash PHP function) `Z`
- [x] Log-out `Z`
- [x] Add reCAPTCHA v2 `Z`
- [x] Fix hard-coded security question `M`
- [x] Add server-side controls to login and registration form `M`
- [x] Change password `M`
- [x] Recover password `M`
- [ ] Unique "standard" login page in checkout `R`
- [ ] Improve failed attempts mail `R`

## Order
- [x] Client-side validation of credit card attributes `R`
- [x] Check duplicate credit card number
- [ ] Use same style for credit card form `M`
- [ ] Check items are not already bought `R`

## Other
- [x] User profile page
- [x] Owned e-books page
- [x] Orders page
- [ ] Escape all user-inserted fields with htmlspecialchars before writing
   them to HTML page. `Z`
- [ ] Add secret_questions to population script `Z`
- [ ] cart items as cookies (persist in session on checkout) `R`
- [ ] Docker-ify `M`
- [ ] "pentesting" `Z`
