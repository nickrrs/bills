/**
 * Utilitário para fazer requisições API com headers automáticos
 * Inclui CSRF token, credentials e headers padrão
 */

function getCsrfToken(): string {
    // Primeiro tenta obter do meta tag
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Se não encontrar, tenta obter do cookie XSRF-TOKEN (Laravel envia automaticamente)
    if (!token) {
        const cookies = document.cookie.split(';');
        const xsrfCookie = cookies.find(cookie => cookie.trim().startsWith('XSRF-TOKEN='));
        if (xsrfCookie) {
            token = decodeURIComponent(xsrfCookie.split('=')[1]);
        }
    }

    return token || '';
}

interface ApiRequestOptions extends RequestInit {
    skipCsrf?: boolean;
}

/**
 * Faz uma requisição fetch com headers automáticos para API Laravel
 */
export async function apiRequest(url: string, options: ApiRequestOptions = {}): Promise<Response> {
    const { skipCsrf = false, headers = {}, ...restOptions } = options;

    const defaultHeaders: Record<string, string> = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(headers as Record<string, string>),
    };

    // Adiciona CSRF token apenas para métodos que modificam dados
    if (!skipCsrf && restOptions.method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(restOptions.method)) {
        defaultHeaders['X-CSRF-TOKEN'] = getCsrfToken();
    }

    return fetch(url, {
        ...restOptions,
        headers: defaultHeaders,
        credentials: 'include',
    });
}

/**
 * GET request helper
 */
export async function apiGet(url: string, options: ApiRequestOptions = {}): Promise<Response> {
    return apiRequest(url, { ...options, method: 'GET' });
}

/**
 * POST request helper
 */
export async function apiPost(url: string, data?: any, options: ApiRequestOptions = {}): Promise<Response> {
    return apiRequest(url, {
        ...options,
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers,
        },
        body: data ? JSON.stringify(data) : undefined,
    });
}

/**
 * PUT request helper
 */
export async function apiPut(url: string, data?: any, options: ApiRequestOptions = {}): Promise<Response> {
    return apiRequest(url, {
        ...options,
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers,
        },
        body: data ? JSON.stringify(data) : undefined,
    });
}

/**
 * DELETE request helper
 */
export async function apiDelete(url: string, options: ApiRequestOptions = {}): Promise<Response> {
    return apiRequest(url, { ...options, method: 'DELETE' });
}

