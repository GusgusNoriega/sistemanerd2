<!-- resources/views/components/image-gallery.blade.php -->
<div id="image-gallery" class="grid grid-cols-2 md:grid-cols-4 gap-4 overflow-auto h-96">
    <!-- Aquí se insertarán las imágenes -->
</div>
<div id="loading" class="text-center my-4 hidden">
    Cargando más imágenes...
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let page = 1;
        let perPage = 15;
        let loading = false;
        const gallery = document.getElementById('image-gallery');
        const loadingIndicator = document.getElementById('loading');

        // Función para cargar imágenes
        async function loadImages() {
            if (loading) return;
            loading = true;
            loadingIndicator.classList.remove('hidden');

            try {
                const response = await fetch(`/api/images?page=${page}&per_page=${perPage}`);
                const data = await response.json();

                data.data.forEach(image => {
                    const img = document.createElement('img');
                    img.src = `/storage/${image.path}`;
                    img.alt = image.name;
                    img.classList.add('w-full', 'h-auto', 'object-cover');
                    gallery.appendChild(img);
                });

                // Incrementamos la página si hay más
                if (data.next_page_url) {
                    page++;
                    observer.observe(document.querySelector('#image-gallery img:last-child'));
                } else {
                    // No hay más páginas
                    observer.disconnect();
                }
            } catch (error) {
                console.error('Error al cargar imágenes:', error);
            } finally {
                loading = false;
                loadingIndicator.classList.add('hidden');
            }
        }

        // Intersection Observer para detectar cuando el último elemento es visible
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                observer.unobserve(entries[0].target);
                loadImages();
            }
        }, {
            root: gallery,
            rootMargin: '0px',
            threshold: 1.0
        });

        // Cargar las imágenes iniciales
        loadImages();
    });
</script>
@endpush