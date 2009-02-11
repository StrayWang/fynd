package cn.fishtrees.fynd.mobile;

import java.util.Hashtable;
import java.util.Stack;
import java.util.Vector;

import cn.fishtrees.fynd.mobile.ui.Console;

public class MemoryManager {
	/**
	 * 释放内存，当内存小于addtionMemory时候，进行垃圾回收，主动回收
	 * 
	 * @param addtionMemory
	 */
	public final static void release(long addtionMemory) {
		long freeMemory = Runtime.getRuntime().freeMemory();
		// 如果空闲的内存小于指定的内存，则回收
		if (freeMemory <= addtionMemory) {
			Console.WriteLine("回收前的空闲内存==>" + MemoryManager.getFreeMemoryKByte());
			System.gc();
			Console.WriteLine("回收后的空闲内存==>" + MemoryManager.getFreeMemoryKByte());
		}
	}
	/**
	 * 强制执行垃圾回收
	 */
	public final static void releaseForce(){
		Console.WriteLine("回收前的空闲内存==>" + MemoryManager.getFreeMemoryKByte());
		System.gc();
		Console.WriteLine("回收后的空闲内存==>" + MemoryManager.getFreeMemoryKByte());
	}

	/**
	 * 释放指定缓存对象
	 * 
	 * @param addtionMemory
	 * @param cache
	 */
	public final static void release(long addtionMemory, Object cache) {
		release(addtionMemory);
		if (cache != null) {
			if (cache instanceof java.util.Hashtable) {
				Hashtable i = (Hashtable) cache;
				i.clear();
			} else if (cache instanceof java.util.Vector) {
				Vector i = (Vector) cache;
				i.removeAllElements();
			} else if (cache instanceof java.util.Stack) {
				Stack i = (Stack) cache;
				i.removeAllElements();
			}
			cache = null;
			System.gc();
		}
	}

	/**
	 * 得到系统空闲内存，单位是k
	 * 
	 * @return 返回空闲内存的大小
	 */
	public final static String getFreeMemoryKByte() {
		return getFreeMemoryByte() / 1024 + "k";
	}

	public final static long getFreeMemoryByte() {
		return Runtime.getRuntime().freeMemory();
	}

	// private static MemoryManager instance;
	// private static TimerTask task;

	// /**
	// * 自动回收内存机制,此方法，应该在系统第一次调用的时候调用，如果重复调用则会抛出异常
	// *
	// * @param time
	// */
	// public static void autoGC(long time) throws java.lang.RuntimeException {
	// if (instance == null) {
	// instance = new MemoryManager();
	// task = TimerTaskManager.getInstace().create(instance, time);
	// } else
	// throw new RuntimeException("GC is starting...");
	// }
	//
	// public static void colse() {
	// if (instance != null)
	// instance = null;
	// if (task != null) {
	// task.cancel();
	// task = null;
	// }
	//
	// }
	//
	// /**
	// * 实现对内存的自动化管理
	// */
	// public void run() {
	// // 小于12k内存的时候释放内存
	// release(1200000);
	//
	// }
}
