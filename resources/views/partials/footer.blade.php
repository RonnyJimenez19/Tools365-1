{{-- resources/views/partials/footer.blade.php --}}
{{-- Incluir en app.blade.php con: @include('partials.footer') --}}

<footer class="bg-dark-custom text-white mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="fw-bold">Tools<span class="text-accent">365</span></h4>
                <p class="text-white-50">Todas tus herramientas en un solo lugar. Renta, compra, vende o subasta maquinaria industrial.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Empresa</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Nosotros</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Carreras</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contacto</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Servicios</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Rentar</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Comprar</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Vender</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Subastar</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="text-accent mb-3">Soporte</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Centro de Ayuda</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacidad</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Términos</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-12 mb-4">
                <h6 class="text-accent mb-3">Contacto</h6>
                <ul class="list-unstyled">
                    <li><div class="footer-contact-item"><i class="bi bi-envelope"></i><span>contacto@tools365.com</span></div></li>
                    <li><div class="footer-contact-item"><i class="bi bi-telephone"></i><span>999 104 1723</span></div></li>
                    <li><div class="footer-contact-item"><i class="bi bi-geo-alt"></i><span>Mérida, Yucatán</span></div></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-white-50">
            <p class="mb-0">&copy; {{ date('Y') }} Tools365. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>