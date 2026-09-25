<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cosmic Fashion')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/footer.css') }}">
    @stack('styles')
</head>
<body>
    <header>
        @include('partials.header')
        @if(!request()->is('login') && !request()->is('register'))
            @include('partials.menu')
        @endif
    </header>
    
    <main>
        @yield('content')
    </main>
    
    @if(!request()->is('login') && !request()->is('register'))
        <footer>
            @include('partials.footer')
        </footer>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function createToastContainer() {
            let container = document.getElementById('global-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'global-toast-container';
                container.style.position = 'fixed';
                container.style.top = '100px';
                container.style.right = '20px';
                container.style.zIndex = '1060';
                document.body.appendChild(container);
            }
            return container;
        }

        function showGlobalToast(msg, type = 'success') {
            const container = createToastContainer();
            const toast = document.createElement('div');
            toast.className = 'neo-card bg-white mb-3 shadow-lg p-3 rounded-4';
            toast.style.width = '300px';
            toast.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            
            let icon = '';
            let color = '';
            let title = '';
            if(type === 'error') {
                icon = '<i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>';
                color = 'text-danger';
                title = 'Lỗi';
                toast.classList.add('border-danger');
            } else {
                icon = '<i class="bi bi-check-circle-fill text-success fs-5"></i>';
                color = 'text-success';
                title = 'Thành công';
            }

            toast.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        ${icon}
                        <h6 class="fw-bolder m-0 ${color} text-uppercase">${title}</h6>
                    </div>
                    <button type="button" class="btn-close" style="width: 10px; height: 10px;" aria-label="Close"></button>
                </div>
                <div class="fw-medium text-dark" style="font-size: 0.9rem;">${msg}</div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            });
            
            toast.querySelector('.btn-close').onclick = () => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            };

            setTimeout(() => {
                if(toast.parentElement) {
                    toast.style.transform = 'translateX(120%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }

        function showConfirmToast(msg, onConfirm) {
            const container = createToastContainer();
            const toast = document.createElement('div');
            toast.className = 'neo-card bg-white mb-3 shadow-lg p-3 rounded-4';
            toast.style.width = '320px';
            toast.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            
            toast.innerHTML = `
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-question-circle-fill text-warning fs-5"></i>
                    <h6 class="fw-bolder m-0 text-dark text-uppercase">Xác nhận</h6>
                </div>
                <div class="fw-medium text-dark mb-3" style="font-size: 0.9rem;">${msg}</div>
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-outline-dark btn-sm rounded-3 px-3 fw-bold btn-huy">Hủy</button>
                    <button class="btn btn-danger btn-sm rounded-3 px-3 fw-bold btn-xac-nhan">Đồng ý</button>
                </div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            });

            const removeToast = () => {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            };

            toast.querySelector('.btn-huy').onclick = removeToast;
            toast.querySelector('.btn-xac-nhan').onclick = () => {
                removeToast();
                onConfirm();
            };
        }
    </script>
    @stack('scripts')
</body>
</html>