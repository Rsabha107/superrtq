import { forwardRef, TextareaHTMLAttributes } from 'react';

export default forwardRef(function TextArea(
    { className = '', ...props }: TextareaHTMLAttributes<HTMLTextAreaElement>,
    ref: React.Ref<HTMLTextAreaElement>,
) {
    return (
        <textarea
            {...props}
            ref={ref}
            className={
                'rounded-md border-gray-300 shadow-sm focus:border-rtq-maroon focus:ring-rtq-maroon ' +
                className
            }
        />
    );
});
