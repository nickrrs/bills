<template>
    <div class="hidden lg:flex items-center justify-center h-[500px]">
        <div class="hero-orb">
            <div class="hero-orb-layer"></div>
            <div class="hero-orb-layer"></div>
            <div class="hero-orb-layer"></div>
        </div>
    </div>
</template>

<style scoped>
/* Hero Orb Animation */
.hero-orb {
    width: 400px;
    height: 400px;
    position: relative;
    transform-style: preserve-3d;
    animation: rotate-orb 20s infinite linear;
}
.hero-orb-layer {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid;
    background-position: center;
    background-size: cover;
    animation: morph-shape 8s infinite alternate;
}
.hero-orb-layer:nth-child(1) {
    border-color: rgba(99, 102, 241, 0.5);
    animation-delay: -4s;
    transform: translateZ(-50px) scale(0.8);
}
.hero-orb-layer:nth-child(2) {
    border-color: rgba(168, 85, 247, 0.5);
    animation-delay: -2s;
    transform: translateZ(0px) scale(0.9);
}
.hero-orb-layer:nth-child(3) {
    border-color: rgba(236, 72, 153, 0.5);
    transform: translateZ(50px) scale(1);
}

@keyframes rotate-orb {
    from { transform: rotateY(0deg) rotateX(10deg); }
    to { transform: rotateY(360deg) rotateX(10deg); }
}

@keyframes morph-shape {
    0% { border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; }
    50% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
    100% { border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; }
}
</style>

