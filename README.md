<h1>Ratemovies</h1>

<p>Esta aplicación te brinda la oportunidad de explorar el mundo del cine a través de la escritura de reseñas de películas, al mismo tiempo que funciona como una mini red social que fomenta la interacción entre usuarios.</p>

<p align="center">
  Creado por: <a href="https://www.linkedin.com/in/wiillmattos/">Will Mattos</a>
</p>
<p align="center">
  Visita la página en: <a href="http://54.76.44.134/">Ratemovies</a>
</p>

<h2>Requisitos</h2>

<ul>
  <li>PHP >= 8.x</li>
  <li>Composer</li>
  <li>Symfony CLI (opcional pero recomendado)</li>
</ul>

<h2>Instalación</h2>

<ol>
  <li>Clona el repositorio:</li>
  <pre><code>git clone https://github.com/willmattos/ratemovies.git</code></pre>

  <li>Accede al directorio del proyecto:</li>
  <pre><code>cd ratemovies</code></pre>

  <li>Instala las dependencias de Composer:</li>
  <pre><code>composer install</code></pre>

  <li>Configura el archivo <code>.env</code> para el servidor de correo. Si no tienes un servidor de correo, puedes probar Mailtrap y seleccionar Symfony 5+:</li>
  <pre><code>MAILER_DSN=el_que_te_ofrece_mailtrap</code></pre>

  <li>Crea la base de datos y ejecuta las migraciones:</li>
  <pre><code>php bin/console doctrine:database:create</code></pre>
  <pre><code>php bin/console doctrine:migrations:migrate</code></pre>

  <li>Inicia el servidor local de Symfony (opcional):</li>
  <pre><code>symfony server:start</code></pre>
  <p>o</p>
  <pre><code>php bin/console server:run</code></pre>
  <p>Visita <a href="http://localhost:8000">http://localhost:8000</a> en tu navegador.</p>

  <li>Agrega datos de prueba (opcional):</li>
  <ol>
    <li>Abre el archivo <code>RatemoviesController.php</code> en <code>/src/controller/</code>.</li>
    <li>Al final del archivo, descomenta las líneas desde la 182 hasta la 191.</li>
    <li>Debería verse así:</li>
    <pre><code>#[Route('/agregarDatos', name: 'agregarDatos')]
public function agregarDatos()
{
    $filePath = 'sql/ratemovies.sql';
    if (file_exists($filePath)) {
        $sqlContent = file_get_contents($filePath);
        $this->connection->exec($sqlContent);
    }
    return $this->redirectToRoute('home');
}</code></pre>
    <li>Cambiar el nombre de los archivos de prueba</li>
    <pre><code>cp -r public/Pruebas public/Contenido</code></pre>
    <li>Accede a <a href="http://localhost:8000/agregarDatos">http://localhost:8000/agregarDatos</a></li>
  </ol>
</ol>

<p>¡Listo! Ahora puedes disfrutar de tu aplicación Symfony Movie Reviews. ¡Explora, escribe reseñas y comparte tu pasión por el cine con otros usuarios!</p>
