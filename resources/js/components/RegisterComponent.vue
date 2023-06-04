<template>
    <div class="row">
        <!-- other fields -->

        <div class="col-md-6 col-12">
            <div>
                <label class="label" for="name">Nombre y apellidos</label>
                <input class="controlinput" type="text" id="name" name="name" maxlength="32" placeholder=" " autocomplete="on" autocapitalize="off" autocorrect="off" required>
                <ul class="errors"></ul>
            </div>
            <div>
                <label class="label" for="name">Email</label>
                <input class="controlinput" type="email" id="email" name="email" maxlength="32" placeholder=" " autocomplete="on" autocapitalize="off" autocorrect="off" required>
                <ul class="errors"></ul>
            </div>
            <div>
                <label class="label" for="name">Direccion</label>
                <input class="controlinput" type="text" id="address" name="address" maxlength="32" placeholder=" " autocomplete="on" autocapitalize="off" autocorrect="off" required>
                <ul class="errors"></ul>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div>
                <label class="label" for="name">Ciudad</label>
                <input class="controlinput" type="text" id="ciudad" name="ciudad" maxlength="32" placeholder=" " autocomplete="on" autocapitalize="off" autocorrect="off" required>
                <ul class="errors"></ul>
            </div>
            <div>
                <label class="label" for="name">Código postal</label>
                <input class="controlinput" type="text" id="cp" name="cp" maxlength="32" placeholder=" " autocomplete="on" autocapitalize="off" autocorrect="off" required>
                <ul class="errors"></ul>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <label class="label" for="pass">Contraseña</label>
            <input v-model="password" @input="passwordTouched = true" type="password" id="pass" name="password" maxlength="32" pattern="[A-Za-z0-9]+" autocomplete="off" autocapitalize="off" autocorrect="off" required>
            <p v-if="passwordError" class="error">{{ passwordError }}</p>
        </div>
        <div class="col-md-6 col-12">
            <label class="label" for="pass">Repetir contraseña</label>
            <input v-model="passwordConfirmation" @input="passwordConfirmationTouched = true" type="password" id="passc" name="password_confirmation" maxlength="32" pattern="[A-Za-z0-9]+" autocomplete="off" autocapitalize="off" autocorrect="off" required>
            <p v-if="passwordConfirmationError" class="error w-100">{{ passwordConfirmationError }}</p>
        </div>
        <!-- other fields -->
    </div>
    </template>

    <script>
    export default {
        data() {
            return {
                password: '',
                passwordConfirmation: '',
                passwordTouched: false,
                passwordConfirmationTouched: false
            }
        },
        computed: {
            passwordError() {
                if (!this.password && this.passwordTouched) {
                    return 'Contraseña requerida.'
                } else if (this.password.length < 8 && this.passwordTouched) {
                    return 'La contraseña debe tener al menos 8 caracteres.'
                }
                return null
            },
            passwordConfirmationError() {
                if (!this.passwordConfirmation && this.passwordConfirmationTouched) {
                    return 'Confirmación de contraseña requerida.'
                } else if (this.passwordConfirmation.length < 8 && this.passwordConfirmationTouched) {
                    return 'La confirmación de la contraseña debe tener al menos 8 caracteres.'
                } else if (this.password !== this.passwordConfirmation) {
                    return 'Las contraseñas no coinciden.'
                }
                return null
            }
        }
    }
    </script>

    <style scoped>
    div .error {
        text-align: left !important;
        color: red;
        align-self: flex-start !important;
        margin: 0;
    }
    </style>
