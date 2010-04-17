<?php defined('SYSPATH') or die('No direct script access.');
/**
 * MongoDB log writer
 *
 * @package     Kohana
 * @category    Logging
 * @author      Ben Haan <ben@benhaan.com>
 */
class Kohana_Log_Mongo extends Kohana_Log_Writer {

	// Collection used to store log entries
	protected $_collection;

	// Stores connection state
	protected $_connected;

	/**
	 * Creates a new Mongo logger
	 *
	 * @param   string   collection name
	 * @param   string   database name
	 * @param   string   server name or ip address
	 * @throws  Kohana_Exception
	 * @return  void
	 */
	public function __construct($collection = NULL, $database = 'app_logs', $server = 'loalhost')
	{
		try
		{
			// Create new connection to MongoDB server
			$mongo = new Mongo($server);
			$this->_connected = TRUE;
		}
		catch (MongoConnectionException $e)
		{
			$this->_connected = FALSE;
		}

		// Make sure we are connected a server
		if ( ! $this->_connected)
			throw new Kohana_Exception('Unable to connect to the MongoDB server on :server', array(':server' => $server));

		// Set a default collection if no collection is specified
		if ($collection == NULL)
			$collection = 'Logs_'.date('Y_m');

		// Select database and collection to store logs
		$this->_collection = $mongo->$database->$collection;
	}

	/**
	 * Writes each message to the MongoDB Collection
	 *
	 * @param   array   messages
	 * @return  void
	 */
	public function write(array $messages)
	{
		// Write message into the collection
		$this->_collection->insert($messages);
	}
}