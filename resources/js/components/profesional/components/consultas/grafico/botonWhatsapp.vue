<template>
	<div class="whatsapp-icon" title="Whatsapp de recepción">
		<a href="https://wa.me/51996644350/" target="_blank" >
			<img src="/img/whastapp.png" alt="WhatsApp">
		</a>
	</div>
</template>
<style>
  .whatsapp-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 100;
  }

  .whatsapp-icon img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    transition: all 0.3s ease;
  }

  .whatsapp-icon img:hover {
      transform: scale(1.1);
  }
</style>