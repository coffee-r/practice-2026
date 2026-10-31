"use client"

import { zodResolver } from "@hookform/resolvers/zod"
import { useForm } from "react-hook-form"
import { z } from "zod"
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "./ui/form"
import { Input } from "./ui/input"
import { Button } from "./ui/button"

const itemSchema = z.object({
    name: z.string().min(3, "3文字以上で入力してください。")
})

type ItemFormValues = z.infer<typeof itemSchema>

interface TodoFormProps {
    onAddItem: (newItem: ItemFormValues) => void; // アイテム追加用関数を受け取る
}

export function TodoForm({ onAddItem }: TodoFormProps) {
    
    const form = useForm<ItemFormValues>({
        resolver: zodResolver(itemSchema),
        defaultValues: ({
            name: ""
        })
    })
    
    function onSubmit(data: ItemFormValues) {
        console.log("item:", data.name)
        onAddItem(data)
        form.reset();
    }
    

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="pt-4 pb-8">
            <FormField
                control={form.control}
                name="name"
                render={({ field }) => (
                <FormItem>
                    <FormLabel>ToDo</FormLabel>
                    <FormControl>
                        <div className="flex gap-2"> {/* ← 横並びにするコンテナ */}
                            <Input placeholder="やることを入力…" {...field} />
                            <Button type="submit" className="mr-2">道具を追加</Button>
                        </div>
                    </FormControl>
                    <FormMessage />
                </FormItem>
                )}
            />
            </form>
      </Form>
    )
  }