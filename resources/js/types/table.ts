export interface TableHeaderItem {
    label: string;
    className?: string;
}

export interface TableBodyItem<T> {
    label: string;
    value?: (user: T) => React.ReactNode;
    render?: (user: T) => React.ReactNode;
}