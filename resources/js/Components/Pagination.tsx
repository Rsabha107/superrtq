import { Link } from '@inertiajs/react';
import { PaginationLink } from '@/types';

export default function Pagination({ links }: { links: PaginationLink[] }) {
    if (links.length <= 3) {
        return null;
    }

    return (
        <div className="mt-4 flex flex-wrap gap-1">
            {links.map((link, index) => (
                <Link
                    key={index}
                    href={link.url ?? '#'}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                    preserveScroll
                    className={
                        'rounded-md px-3 py-1 text-sm ' +
                        (link.active
                            ? 'bg-rtq-maroon text-white'
                            : link.url
                              ? 'bg-white text-gray-600 hover:bg-gray-100'
                              : 'cursor-not-allowed bg-white text-gray-300')
                    }
                />
            ))}
        </div>
    );
}
