import { User } from "@/types/model/user"

export interface Blog {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  title: string
  slug: string | null
  content: unknown
  image: string | null
  meta_title: string | null
  meta_description: string | null
  meta_keywords: string | null
  is_published: boolean
  published_at: string | null
  // relations
  comments: CommentBlog[]
}

export interface CommentBlog {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  deleted_at: string | null
  blog_id: number
  user_id: number
  parent_id: number | null
  content: string
  answer_to: number | null
  // relations
  reports: Report[]
  replies: CommentBlog[]
  user: User
  blog: Blog
  answer_to_user: User
}
