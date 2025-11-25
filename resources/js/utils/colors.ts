/**
 * Utilitários para trabalhar com cores do tema
 */

/**
 * Converte uma cor hexadecimal para rgba com opacidade
 * @param hex - Cor hexadecimal (ex: '#6366f1' ou '6366f1')
 * @param opacity - Opacidade de 0 a 1 (ex: 0.2 para 20%)
 * @returns String rgba (ex: 'rgba(99, 102, 241, 0.2)')
 */
export function hexToRgba(hex: string, opacity: number): string {
    // Remove o # se presente
    const cleanHex = hex.replace('#', '');

    // Converte para RGB
    const r = parseInt(cleanHex.substring(0, 2), 16);
    const g = parseInt(cleanHex.substring(2, 4), 16);
    const b = parseInt(cleanHex.substring(4, 6), 16);

    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
}

/**
 * Obtém o valor de uma variável CSS do documento
 * @param variableName - Nome da variável CSS (ex: '--accent-primary')
 * @param fallback - Valor padrão caso a variável não exista
 * @returns Valor da variável CSS ou fallback
 */
export function getCssVariable(variableName: string, fallback: string = ''): string {
    return getComputedStyle(document.documentElement)
        .getPropertyValue(variableName)
        .trim() || fallback;
}

/**
 * Obtém a cor accent-primary atual do tema
 * @param fallback - Cor padrão caso não encontre (padrão: indigo)
 * @returns Cor hexadecimal
 */
export function getAccentPrimaryColor(fallback: string = '#6366f1'): string {
    return getCssVariable('--accent-primary', fallback);
}

/**
 * Obtém a cor accent-primary com opacidade
 * @param opacity - Opacidade de 0 a 1 (padrão: 0.2 = 20%)
 * @param fallback - Cor padrão caso não encontre
 * @returns String rgba
 */
export function getAccentPrimaryWithOpacity(
    opacity: number = 0.2,
    fallback: string = 'rgba(99, 102, 241, 0.2)'
): string {
    const accentColor = getAccentPrimaryColor();

    // Se não encontrar a cor ou for o fallback padrão, retorna o fallback fornecido
    if (!accentColor) {
        return fallback;
    }

    // Se a cor for válida, converte para rgba
    try {
        return hexToRgba(accentColor, opacity);
    } catch (error) {
        console.error('Erro ao converter cor para rgba:', error);
        // Se houver erro na conversão, retorna o fallback
        return fallback;
    }
}

/**
 * Obtém a cor accent-primary-light atual do tema
 * @param fallback - Cor padrão caso não encontre
 * @returns Cor hexadecimal
 */
export function getAccentPrimaryLightColor(fallback: string = '#eef2ff'): string {
    return getCssVariable('--accent-primary-light', fallback);
}

