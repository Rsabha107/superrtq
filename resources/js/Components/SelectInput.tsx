import { forwardRef, SelectHTMLAttributes } from 'react';

export default forwardRef(function SelectInput(
    {
        className = '',
        children,
        ...props
    }: SelectHTMLAttributes<HTMLSelectElement>,
    ref: React.Ref<HTMLSelectElement>,
) {
    return (
        <select
            {...props}
            ref={ref}
            className={
                'rounded-md border-gray-300 shadow-sm focus:border-rtq-maroon focus:ring-rtq-maroon ' +
                className
            }
        >
            {children}
        </select>
    );
});
