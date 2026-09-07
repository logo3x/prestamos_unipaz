import Swal from 'sweetalert2';

const swalBase = Swal.mixin({
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-2xl shadow-xl',
        title: 'text-lg font-semibold text-gray-900',
        htmlContainer: 'text-sm text-gray-600',
        confirmButton:
            'inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 active:scale-[0.97] transition-transform duration-150 ease-out mx-1',
        cancelButton:
            'inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 active:scale-[0.97] transition-transform duration-150 ease-out mx-1',
        actions: 'gap-0',
    },
    showClass: {
        popup: 'animate-fade-in-up',
    },
    hideClass: {
        popup: '',
    },
});

export function confirmar({ titulo, texto, confirmarTexto = 'Confirmar', cancelarTexto = 'Cancelar' }) {
    return swalBase.fire({
        title: titulo,
        text: texto,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmarTexto,
        cancelButtonText: cancelarTexto,
        reverseButtons: true,
        focusCancel: true,
    });
}

export function notificarExito(mensaje) {
    return swalBase.fire({
        title: mensaje,
        icon: 'success',
        timer: 2500,
        showConfirmButton: false,
    });
}

export function notificarError(mensaje) {
    return swalBase.fire({
        title: mensaje,
        icon: 'error',
        confirmButtonText: 'Entendido',
    });
}

document.addEventListener('livewire:init', () => {
    Livewire.on('swal:confirm', ({ titulo, texto, confirmarTexto, cancelarTexto, evento, params }) => {
        confirmar({ titulo, texto, confirmarTexto, cancelarTexto }).then((resultado) => {
            if (resultado.isConfirmed && evento) {
                Livewire.dispatch(evento, params ?? {});
            }
        });
    });

    Livewire.on('swal:exito', ({ mensaje }) => notificarExito(mensaje));
    Livewire.on('swal:error', ({ mensaje }) => notificarError(mensaje));
});
