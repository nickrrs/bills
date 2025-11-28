export function formatCurrency(value: number | string, currency = 'BRL'): string {
    const numericValue = typeof value === 'string' ? Number(value.replace(',', '.')) : value || 0;
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number.isFinite(numericValue) ? numericValue : 0);
}

export function formatCurrencyValue(value: number | string): string {
    if (value === null || value === undefined || value === '') {
        return '0,00';
    }

    let parsedValue: number;

    if (typeof value === 'string') {
        const normalized = value.replace(/\s/g, '').replace(',', '.');
        parsedValue = Number(normalized);
    } else {
        parsedValue = value;
    }

    if (!Number.isFinite(parsedValue)) {
        return '0,00';
    }

    const stringValue = parsedValue.toString();

    if (Math.abs(parsedValue) >= 1e15 || /e\+/i.test(stringValue)) {
        const [integerPart, decimalPart = ''] = stringValue.split('.');
        const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const formattedDecimal = decimalPart.padEnd(2, '0').substring(0, 2);
        return `${formattedInteger},${formattedDecimal}`;
    }

    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(parsedValue);
}

export function formatDate(value: string | Date, options?: Intl.DateTimeFormatOptions): string {
    if (!value) {
        return '';
    }

    const date = value instanceof Date ? value : new Date(value);
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        ...options,
    }).format(date);
}

