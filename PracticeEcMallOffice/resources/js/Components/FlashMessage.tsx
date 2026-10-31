import { usePage } from "@inertiajs/react";

export default function FlashMessage() {
    const { flashSuccessMessage, flashFailMessage } = usePage<any>().props
    return (
        <>
            {flashSuccessMessage && (
                <div className="bg-teal-100 border border-teal-400 text-teal-700 px-4 py-3 rounded relative" role="alert">
                    <strong className="font-bold">{flashSuccessMessage}</strong>
                </div>
            )}
            {flashFailMessage && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong className="font-bold">{flashFailMessage}</strong>
                </div>
            )}
        </>
    )
}