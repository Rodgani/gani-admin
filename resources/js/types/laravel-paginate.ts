export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

/**
 * A generic Laravel pagination response type.
 * Use `T` to define the data type for pagination results.
 */
export interface LaravelPagination<T> {
    current_page: number;
    data: T[]; // Generic type for paginated data
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}
