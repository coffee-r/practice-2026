import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Post } from '@/types/Post';
import { FormEventHandler } from 'react';

export default function PostCard({ post }: {post: Post}) {

    const { delete: destroy } = useForm();

    const deletePost: FormEventHandler = (e) => {
        e.preventDefault();
        destroy(route('posts.destroy', post.post_id));
    };

    return (
        <div className="pt-4">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 text-gray-900">
                        post_id : {post.post_id} <br/>
                        user_name : {post.user_name} <br/>
                        message : {post.message}
                    </div>
                    <form onSubmit={deletePost}>
                        <button type="submit" className="py-2 px-3 ml-4 mb-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-red-500 text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all text-sm">
                            削除
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}
