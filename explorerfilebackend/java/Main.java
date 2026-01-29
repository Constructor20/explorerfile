public class Main {
    public static void main(String[] args) {
        while (true) {
            System.out.println("Hello world !");
            try {
                Thread.sleep(1000); // 1000 ms = 1 seconde
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }
}