<template>
    <!-- Header Section -->
    <header class="header">
        <div class="header-container">
            <h1>RSS Marketplace</h1>
            <nav class="nav">
                <nav v-if="canLogin" class="auth-nav flex flex-1 justify-end">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="auth-button"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="auth-button"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="auth-button"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>What We Are</h2>
            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
        </div>
    </section>

    <section class="about-us">
        <div class="about-us-content">
            <h2>What We Do</h2>
            <p>
                There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
            </p>
            <ul class="features-list">
                <li> > Extensive range of products</li>
                <li> > Trusted sellers and buyers</li>
                <li> > Secure payment options</li>
                <li> > Global shipping and services</li>
            </ul>
        </div>
    </section>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>


<style scoped>
body {
    font-family: monospace;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.header {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav {
    display: flex;
    justify-content: flex-end;
    align-items: center; /* Ensure items are vertically centered */
}

.auth-nav {
    display: flex; /* Make sure the nav items are in a row */
}

.auth-button {
    padding: 10px 20px;
    background-color: gray; 
    color: white;
    border: none;
    border-radius: 5px; 
    margin-left: 10px; 
    text-decoration: none; 
    transition: background-color 0.3s; 
}

.auth-button:hover {
    background-color: #e0241b; 
}

.hero {
    padding: 50px;
    background-color: #f9f9f9;
    text-align: center;
}

.hero-content h2 {
    font-size: 36px;
    margin-bottom: 20px;
}

.hero-content p {
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.about-us {
    padding: 50px;
    background-color: #fff;
    text-align: center;
}

.about-us h2 {
    font-size: 36px;
    margin-bottom: 20px;
}

.about-us p {
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.features-list {
    list-style: none;
    padding: 0;
    margin-top: 20px;
}

.features-list li {
    font-size: 16px;
    margin: 10px 0;
}
</style>
