<script>
    const mensajeFormatoPesos = @json(\App\Support\FormatoNumericoFinanciero::MENSAJE_PESOS);
    const formatoPesosEntrada = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 });

    function normalizarPesosEntrada(valor) {
        const texto = valor.trim();
        if (/^[+-]?[0-9]+$/.test(texto)) {
            return texto;
        }

        const agrupado = /^[-+]?[0-9]{1,3}([., \u00A0\u202F])[0-9]{3}(?:\1[0-9]{3})*$/u.exec(texto);
        return agrupado ? texto.replaceAll(agrupado[1], '') : null;
    }

    function configurarCampoMoneda(campo, esMonetario = () => true) {
        const revisar = (formatear) => {
            if (!esMonetario()) {
                campo.setCustomValidity('');
                return;
            }

            const valor = campo.value.trim();
            const normalizado = normalizarPesosEntrada(valor);
            const formatoInvalido = valor !== '' && normalizado === null;
            campo.setCustomValidity(formatoInvalido ? mensajeFormatoPesos : '');

            if (formatear && !formatoInvalido) {
                campo.value = normalizado === null ? '' : formatoPesosEntrada.format(BigInt(normalizado));
            }
        };

        campo.addEventListener('input', () => revisar(false));
        campo.addEventListener('blur', () => revisar(true));
        revisar(true);
    }
</script>
