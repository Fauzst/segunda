import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"

// React Library
import { useState } from "react"
// Asset
import HeroImage from '../../public/hero-segunda.png'


export function SignupForm({
  className,
  ...props
}: React.ComponentProps<"div">) {
  
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [role, setRole] = useState('');

    const submitSignup = async (e:any) => {
        e.preventDefault();
    
        const data = {
            email,
            password,
            role
        };
    
        try {
            const response = await fetch('http://localhost/cognate/segunda/src/api/signup/signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });
    
            const responseText = await response.text(); 
    
            console.log('Response Text:', responseText); 
    
            if (!response.ok) {
                throw new Error('Failed to fetch');
            }
    
            const result = JSON.parse(responseText); 
    
            if (result.status === 'success') {
                setSuccess(result.message);
                setError('');
            } else {
                setError(result.message);
                setSuccess('');
            }
        } catch (err) {
            setError('Something went wrong.');
            console.log('Error:', err); // Log the error
        }
    };
    

  return (

    <div className={cn("flex flex-col gap-6", className)} {...props}>
      <Card className="overflow-hidden">
        <CardContent className="grid p-0 md:grid-cols-2">
          <form className="p-6 md:p-8">
            <div className="flex flex-col gap-6">
              <div className="flex flex-col items-center text-center">
                <h1 className="text-2xl font-bold">Welcome to Segunda!</h1>
                <p className="text-balance text-muted-foreground">
                  Create new account
                </p>
              </div>

              { !role &&
                <>
                  <div className="flex flex-row items-center text-center gap-3">
                    <div 
                      className="bg-primary p-2 text-secondary font-semibold rounded"
                      onClick={() => {
                        setRole('User');
                      }}
                    >Signup as a User</div>
                    <div 
                      className="bg-secondary text-primary p-2 border-2 border-primary rounded"
                      onClick={() => {
                        setRole('Seller');
                      }}
                    >Signup as a Seller</div>
                  </div>
                </>
              }

              { role &&
                  <>
                      <div className="grid gap-2">
                    <Label htmlFor="email">Email</Label>
                    <Input
                      id="email"
                      type="email"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      placeholder="m@example.com"
                      required
                    />
                  </div>
                  <div className="grid gap-2">
                    <div className="flex items-center">
                      <Label htmlFor="password">Password</Label>
                      <a
                        href="#"
                        className="ml-auto text-sm underline-offset-2 hover:underline"
                      >
                    
                      </a>
                    </div>
                    <Input 
                        id="password" 
                        type="password" 
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required />
                  </div>
                  <Button type="submit" className="w-full" onClick={submitSignup}>
                    Signup
                  </Button>
                  <div className="text-center text-sm">
                    Back to <a 
                      href="" 
                      className="underline"
                      onClick={() => {
                        setRole('');
                      }}
                    >Role Selection</a><br />
                  </div>
                </>
              }

              <div className="text-center text-sm">
                Alrady have an account?{" "}
                <a href="/" className="underline underline-offset-4">
                  Log in
                </a>
              </div>
            </div>
          </form>
          <div className="relative hidden bg-muted md:block">
            <img
              src={HeroImage}
              alt="Image"
              className="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
            />
          </div>
        </CardContent>
      </Card>
      <div className="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
        By clicking continue, you agree to our <a href="#">Terms of Service</a>{" "}
        and <a href="#">Privacy Policy</a>.
      </div>
    </div>
  )
}