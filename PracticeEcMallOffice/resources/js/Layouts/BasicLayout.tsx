import { PropsWithChildren, ReactNode } from 'react';
import { User } from '@/types';
import SideMenu from '@/Components/SideMenu';
import FlashMessage from '@/Components/FlashMessage';
import CustomHeader from '@/Components/CustomHeader';

export default function BasicLayout({ user, children }: PropsWithChildren<{ user: User }>) {
    return (
        <div className="grid min-h-screen w-full lg:grid-cols-[280px_1fr]">
            <SideMenu user={user} />
            <div className="flex flex-col">
                <CustomHeader user={user} />
                <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-6">
                    <FlashMessage />
                    {children}
                </main>
            </div>
        </div>
    );
}

