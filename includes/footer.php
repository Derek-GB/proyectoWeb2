<footer id="footer" class="py-10" style="background:var(--accent); color:var(--text);">
  <div class="container mx-auto px-6 grid md:grid-cols-3 gap-8 items-center">

    <!-- Columna 1: Info de contacto -->
    <div class="space-y-3 text-left">
      <div class="flex items-center gap-2">
        <span class="text-lg">📍</span>
        <p><strong>Dirección:</strong> <?= h($config['direccion']) ?></p>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-lg">📞</span>
        <p><strong>Teléfono:</strong> <?= h($config['telefono']) ?></p>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-lg">✉️</span>
        <p><strong>Email:</strong> <?= h($config['email']) ?></p>
      </div>
    </div>

    <!-- Columna 2: Logo y redes -->
    <div class="text-center space-y-2">
      <img src="<?= "/proyecto/" . h($config['iconoBlanco'] ?? $config['iconoPrincipal']) ?>" alt="logo"
        class="mx-auto h-20 w-20 object-cover rounded">
      <h4 class="font-bold uppercase">UTN Solutions<br>Real State</h4>
      <div class="flex justify-center gap-4 mt-3">
        <a href="<?= h($config['facebook']) ?>" target="_blank" class="hover:opacity-80">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook"
            class="h-8">
        </a>
        <a href="<?= h($config['youtube']) ?>" target="_blank" class="hover:opacity-80">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg" alt="YouTube" class="h-8">
        </a>
        <a href="<?= h($config['instagram']) ?>" target="_blank" class="hover:opacity-80">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram"
            class="h-8">
        </a>
      </div>
    </div>

    <!-- Columna 3: Formulario de contacto -->
    <div class="bg-white/20 rounded p-4" id="contacto">
      <h4 class="text-lg font-bold mb-3">Contactanos</h4>
      <form method="post" action="/proyecto/correo.php" class="space-y-2">
        <input type="text" name="nombre" placeholder="Nombre" class="w-full p-2 rounded text-black" required>
        <input type="email" name="email" placeholder="Email" class="w-full p-2 rounded text-black" required>
        <input type="text" name="telefono" placeholder="Teléfono" class="w-full p-2 rounded text-black">
        <textarea name="mensaje" placeholder="Mensaje" class="w-full p-2 rounded text-black" required></textarea>
        <button type="submit" class="w-full py-2 rounded bg-black text-white font-semibold">Enviar</button>
      </form>
    </div>
  </div>

  <!-- Derechos reservados -->
  <div class="text-center mt-8 font-bold">&copy; <?= date('Y') ?> Derechos Reservados & Anthony y Derek™</div>
</footer>
</body>

</html>