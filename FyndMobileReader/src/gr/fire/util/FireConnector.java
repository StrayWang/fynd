/*
 * Fire (Flexible Interface Rendering Engine) is a set of graphics widgets for creating GUIs for j2me applications. 
 * Copyright (C) 2006-2008 Bluevibe (www.bluevibe.net)
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */


/*
 * Created on Sep 14, 2006
 *
 */
package gr.fire.util;

import java.io.ByteArrayInputStream;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Vector;

import javax.microedition.io.Connection;
import javax.microedition.io.Connector;
import javax.microedition.rms.RecordEnumeration;
import javax.microedition.rms.RecordStore;
import javax.microedition.rms.RecordStoreNotFoundException;

/**
 * FireIO is a utility class containing a set of I/O supporting methods.
 * It also acts as a cache for loaded images. If an image is loaded once and is later requested again, the old instance will be returned.
 * This minimizes the memory requirements of an application GUI, without extra efford on the developer side.  
 * @author padeler
 *
 */
public class FireConnector
{
	public FireConnector()
	{
	}

	/**
	 * 
	 * Lists all record stores of this middlet.
	 *  
	 * @return
	 * 
	 */
	public Vector rmslist()  
	{
		Vector res = new Vector();
		try{
			String []names = RecordStore.listRecordStores();
			if(names!=null)
			{
				for(int i =0 ;i<names.length;++i)
				{
					String fileName = names[i];
					res.addElement(fileName);
				}
			}
		}catch(Exception e)
		{
			Log.logError("List Files Error",e);
		}
		return res;
	}
	
	public void rmsDelete(String file) throws Exception
	{
		RecordStore.deleteRecordStore(file);
	}
	
	
	public int[] rmsSize(String name)
	{
		RecordStore rs =null;
		try{
			rs = RecordStore.openRecordStore(name,false);
			return new int[]{rs.getSize(),rs.getSizeAvailable()};
		}catch(Exception e)
		{
			Log.logError("Failed to get size for RecordStore "+name,e);
		}finally{
			try{if(rs!=null) rs.closeRecordStore();}catch(Throwable e){}
		}
		return new int[]{0,0};
	}
	
	public int rmsUsedSpace()
	{
		String recordStores[] = RecordStore.listRecordStores();
		if(recordStores!=null)
		{
			int sum=0;
			for(int i =0 ;i<recordStores.length;++i)
			{
				sum+= rmsSize(recordStores[i])[0];
			}
			return sum;
		}
		return 0;
	}

	
	public int rmsFree()
	{
		String recordStores[] = RecordStore.listRecordStores();
		if(recordStores!=null && recordStores.length>0)
		{
			return rmsSize(recordStores[0])[1];
		}
		return 0;
	}
		
	
	public void rmsWrite(String file,byte[] buffer) throws Exception
	{
		RecordStore fr=null;
		try{
			fr = RecordStore.openRecordStore(file,true,RecordStore.AUTHMODE_PRIVATE,true);
			RecordEnumeration re = fr.enumerateRecords(null,null,false);
			if(re.hasNextElement())
			{
				int id = re.nextRecordId();
				fr.deleteRecord(id);	
			}
			fr.addRecord(buffer,0,buffer.length);
		}finally{
			 try{if(fr!=null)fr.closeRecordStore();}catch(Exception e){}
		}
	}

	public InputStream openInputStream(String url) throws IOException
	{
		if(url.startsWith("file://"))
		{
			String file = url.substring(7);
			Log.logDebug("Loading local resource: "+file);
			InputStream in = this.getClass().getResourceAsStream("/"+file);
			if(in==null)
				return rmsRead(file);
			else 
				return in;			
		}
		return Connector.openInputStream(url);
	}
	
	public DataInputStream openDataInputStream(String url) throws IOException
	{
		return new DataInputStream(openInputStream(url));
	}
	
	public OutputStream openOutputStream(String url) throws IOException
	{
		return Connector.openOutputStream(url);
	}
	
	public DataOutputStream openDataOutputStream(String url) throws IOException
	{
		return Connector.openDataOutputStream(url);
	}
	
	public Connection open(String url) throws IOException
	{
		return Connector.open(url);
	}
	
	public Connection open(String url,int mode) throws IOException
	{
		return Connector.open(url,mode);
	}
	
	public Connection open(String url,int mode,boolean timeouts) throws IOException
	{
		return Connector.open(url,mode,timeouts);
	}
	
	
	/**
	 * Reads a recordstore and returns a stream to it.
	 * @param f
	 * @return
	 */
	public InputStream rmsRead(String f) throws IOException
	{
		RecordStore fr=null;
		try{
			fr = RecordStore.openRecordStore(f,false,RecordStore.AUTHMODE_PRIVATE,false);
			RecordEnumeration re = fr.enumerateRecords(null,null,false);
			if(re.hasNextElement())
			{
				return new ByteArrayInputStream(re.nextRecord());
			}
			return null;
		}catch(RecordStoreNotFoundException e){
			// record store does not exist. This is not an error. Just return null.
			return null;
		}catch(Exception e){
			throw new IOException("Recordstore read failed for "+f+". "+e.getClass().getName()+": "+e.getMessage());
		}finally{
			 try{if(fr!=null)fr.closeRecordStore();}catch(Exception e){}
		}
	}
}