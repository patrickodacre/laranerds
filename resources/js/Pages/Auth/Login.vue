<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => {
            form.reset('password')
            // reload the page so we get new scripts, etc. in the <head>
            location.reload()
        }
    });
}
</script>

<template>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <Link :href="route('index_page')">
                                <span><img src="assets/images/logo.png" alt="" height="18"></span>
                            </Link>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Sign In</h4>
                                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
                            </div>

                            <form @submit.prevent="submit">

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input
                                        class="form-control"
                                        type="email"
                                        id="emailaddress"
                                        required=""
                                        placeholder="Enter your email"
                                        v-model="form.email"
                                        >
                                </div>

                                <div class="mb-3">
                                    <Link
                                        :href="route('password.request')"
                                        class="text-muted float-end">
                                        <small>Forgot your password?</small>
                                    </Link>
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            v-model="form.password"
                                            type="password"
                                            id="password"
                                            class="form-control"
                                            placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input
                                            v-model="form.remember"
                                            type="checkbox"
                                            class="form-check-input"
                                            id="checkbox-signin"
                                            checked>
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">
                                Don't have an account?
                                <Link :href="route('register')" class="text-muted ms-1">
                                    <b>Sign Up</b>
                                </Link>
                            </p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
</template>
