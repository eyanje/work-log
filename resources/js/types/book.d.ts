export interface Book {
    id: number;
    title: string;
    bookmarked: boolean;
    created_at: string;
    updated_at: string;
};

export interface Record {
    id: number;
    uid: string;
    content: string;
    started_at: string;
    ended_at?: string;
    created_at: string;
    updated_at: string;
};
